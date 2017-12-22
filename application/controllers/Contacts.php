<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Contacts extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $head = array();
        $data = array();
        if (isset($_POST['message'])) {
            $result = $this->sendEmail();
            if ($result) {
                $this->session->set_flashdata('resultSend', 'Email is sened!');
            } else {
                $this->session->set_flashdata('resultSend', 'Email send error!');
            }
            redirect('contacts');
        }
        $data['googleMaps'] = $this->Home_admin_model->getValueStore('googleMaps');
        $data['googleApi'] = $this->Home_admin_model->getValueStore('googleApi');
        $arrSeo = $this->Public_model->getSeo('contacts');
        $head['title'] = @$arrSeo['title'];
        $head['description'] = @$arrSeo['description'];
        $head['keywords'] = str_replace(" ", ",", $head['title']);
        $this->render('contacts', $head, $data);
    }

    public function sendEmail() {
        $myEmail = $this->Public_model->getEmailVendors();
        $vendor = array();
        foreach ($myEmail as $emailvendor) {
            array_push($vendor, $emailvendor->email);
        }

        $config = Array(  
            'protocol' => 'smtp',  
            'smtp_host' => 'ssl://smtp.googlemail.com',  
            'smtp_port' => 465,  
            'smtp_user' => 'muhammad30hidayah696@gmail.com',   
            'smtp_pass' => '13A13i13U',   
            'mailtype' => 'html',   
            'charset' => 'iso-8859-1');  

        $this->load->library('email', $config);  
        $this->email->set_newline("\r\n");  
        $this->email->from("muhammad30hidayah696@gmail.com", $_POST['name']);   
        $this->email->to($vendor);
        $this->email->subject($_POST['subject']);   
        $this->email->message($_POST['message']);
               
        if (!$this->email->send()) {  
            show_error($this->email->print_debugger());   
        }else{  
            echo 'Success to send email';   
        }
                
        return true;        
        
    }

}
