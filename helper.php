<?php
function is_authenticated(){
    if( isset( $_SESSION['is_logged'] ) && $_SESSION['is_logged'] ) {
        return TRUE;
    }else{
        return FALSE;
    }
} 

?>