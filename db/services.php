<?php
$functions = array(
    'local_message_delete_message' => array(         //tên chức năng dịch vụ web
        'classname'   => 'local_message_external',  //class chứa hàm bên ngoài HOẶC class có không gian tên trong classes/external/XXXX.php
        'methodname'  => 'delete_message',          //tên chức năng bên ngoài
        'classpath'   => 'local/message/externallib.php',  //file có chứa class / chức năng bên ngoài - không bắt buộc nếu sử dụng các class tự động tải không gian tên.
        'description' => 'Deletes a message',    //mô tả có thể đọc được về chức năng dịch vụ web
        'type'        => 'write',                  //quyền cơ sở dữ liệu của chức năng dịch vụ web (đọc, ghi)
        'ajax' => true,        // là dịch vụ có sẵn cho 'internal' ajax calls.
        'capabilities' => '', // danh sách các khả năng được sử dụng bởi hàm được phân tách bằng dấu phẩy.
    ),
);
