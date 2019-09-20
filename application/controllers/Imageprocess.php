<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Imageprocess extends CI_Controller {
    // We can configure in config file
    public $IMGUR_UPLOAD_URL_API_ENDPOINT = "http://localhost/restapi/index.php/api/v1/uploaddata";
    public $IMGUR_CLIENTID = "XXXXXXXXXXXXXXX";

    // Show login Form
    public function index() {
        $input = $this->input->post();
        $data = array();
        $data['username'] = "";
        $data['password'] = "";
        $data['message'] = "";

        if (is_array($input) && count($input)) {
            $username = $this->input->post("username");
            $password = $this->input->post("password");
            $data['username'] = $username;
            $data['password'] = $password;
                
            if ($username == "admin" && $password == "admin1234") {
                $this->IMGUR_CLIENTID = "XXXXXXXXXXXXXXX";
                $this->uploadImage();
                return;
            } else {
                $data['message'] = "Please enter correct username and password.";
            }  
        }
        $this->load->view("login", $data);
    }

    // Showing Input
    public function uploadImage() {
        $this->load->view("imageprocess");
    }

    // Uploading and resizeing Image
    public function upload() {
        $input = $this->input->post();
        $img_path = $this->input->post('path');
        $height = $this->input->post('height');
        $width = $this->input->post('width');

        $image_info = getimagesize($img_path); 
        $mime = $image_info['mime'];
        $mime_arr = explode("/", $mime);
        $ext = $mime_arr[1];
        $staticName = 'sampleImage' . date('YmdHis') . "." . $ext;
        $newName = FCPATH . $staticName;
        $hostedPath = base_url($staticName);
        $newName = $this->convertImageIntoNewSize($img_path, $width, $height, $newName, $mime);
        header("Content-Type: text/html");

        $type = pathinfo($newName, PATHINFO_EXTENSION);
        $data = file_get_contents($newName);
        $res = $this->uploadDataIntoImgur('base64', base64_encode($data), $staticName);

        $res = json_decode($res, 1);
        $data = array();

        $data['imagePath'] = $res['imagePath'];
        $data['source'] = $img_path;
        $data['width'] = $width;
        $data['height'] = $height;
        $this->load->view('success', $data);
    }

    // Convert into new height and width
    function convertImageIntoNewSize($img_path, $width, $height, $newName, $mime) {
        $i = imagecreatefromjpeg($img_path);
        $thumb = $this->thumbnail_box($i, $width, $height);
        imagedestroy($i);

        if(is_null($thumb)) {
            /* image creation or copying failed */
            header('HTTP/1.1 500 Internal Server Error');
            exit();
        }
        header('Content-Type: '. $mime);
        imagejpeg($thumb, $newName);

        return $newName;
    }

    // Download Image
    function thumbnail_box($img, $box_w, $box_h) {
        //create the image, of the required size
        $new = imagecreatetruecolor($box_w, $box_h);
        if($new === false) {
            //creation failed -- probably not enough memory
            return null;
        }
    
    
        //Fill the image with a light grey color
        //(this will be visible in the padding around the image,
        //if the aspect ratios of the image and the thumbnail do not match)
        //Replace this with any color you want, or comment it out for black.
        //I used grey for testing =)
        $fill = imagecolorallocate($new, 200, 200, 205);
        imagefill($new, 0, 0, $fill);
    
        //compute resize ratio
        $hratio = $box_h / imagesy($img);
        $wratio = $box_w / imagesx($img);
        $ratio = min($hratio, $wratio);
    
        //if the source is smaller than the thumbnail size, 
        //don't resize -- add a margin instead
        //(that is, dont magnify images)
        if($ratio > 1.0)
            $ratio = 1.0;
    
        //compute sizes
        $sy = floor(imagesy($img) * $ratio);
        $sx = floor(imagesx($img) * $ratio);
    
        //compute margins
        //Using these margins centers the image in the thumbnail.
        //If you always want the image to the top left, 
        //set both of these to 0
        $m_y = floor(($box_h - $sy) / 2);
        $m_x = floor(($box_w - $sx) / 2);
    
        //Copy the image data, and resample
        //
        //If you want a fast and ugly thumbnail,
        //replace imagecopyresampled with imagecopyresized
        if(!imagecopyresampled($new, $img,
            $m_x, $m_y, //dest x, y (margins)
            0, 0, //src x, y (0,0 means top left)
            $sx, $sy,//dest w, h (resample to this size (computed above)
            imagesx($img), imagesy($img)) //src w, h (the full size of the original)
        ) {
            //copy failed
            imagedestroy($new);
            return null;
        }
        //copy successful
        return $new;
    }

    // Upload file into imgur
    function uploadDataIntoImgur($type, $data, $staticName) {
        if ($type == "base64" || $type == "url") {
            $postData = array();
            $postData['url'] = $this->IMGUR_UPLOAD_URL_API_ENDPOINT;
            $postData['method'] = "POST";
            $postData['header'] = array("Content-Type: multipart/form-data;");
            $postData['data'] = array(
                'Authorization' => "Client-ID {$this->IMGUR_CLIENTID}",
                'image' => $data, 
                'type' => $type, 
                'name' => $staticName,
                'ClientID' => $this->IMGUR_CLIENTID,
                'method' => "POST"
            );
            return Imageprocess::curl_call($postData);
        } else {
            return "Not supporting for $type image Post";
        }
    }

    // Curl Call
    public static function curl_call($postData) {
        $res = array();
        try {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $postData['url']);

            $method = $postData['method'] ?? "GET";
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
            if ($method == "POST") {
                if (isset($postData['data']) && !empty($postData['data'])) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $postData['data']);
                }
            }

            if (isset($postData['header']) && !empty($postData['header'])) {
                curl_setopt($curl, CURLOPT_HTTPHEADER, $postData['header']);
            }

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_ENCODING, "");
            curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
            curl_setopt($curl, CURLOPT_TIMEOUT, 0);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
            curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                $res['message'] = "cURL Error #:" . $err;
                $res['success'] = false;
                if ($response['status']) {
                    $res['status'] = $response['status'];
                }
                return $res;
            } else {
                return $response;
            }
        } catch(Exception $e) {
            $res['message'] = 'Message: ' .$e->getMessage();
            $res['success'] = false;
            if ($response['status']) {
                $res['status'] = $response['status'];
            }
            return $res;
        }
    }
}
?>