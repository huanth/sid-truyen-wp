<?php
/**
 * Calculate time ago in Vietnamese
 */
function sid_truyen_time_ago( $timestamp ) {
    $time_diff = current_time( 'timestamp' ) - $timestamp;

    if ( $time_diff < 60 ) {
        return 'Vừa xong';
    } elseif ( $time_diff < 3600 ) {
        return floor( $time_diff / 60 ) . ' phút trước';
    } elseif ( $time_diff < 86400 ) {
        return floor( $time_diff / 3600 ) . ' giờ trước';
    } elseif ( $time_diff < 2592000 ) { // 30 days
        return floor( $time_diff / 86400 ) . ' ngày trước';
    } elseif ( $time_diff < 31536000 ) { // 365 days
        return floor( $time_diff / 2592000 ) . ' tháng trước';
    } else {
        return floor( $time_diff / 31536000 ) . ' năm trước';
    }
}
