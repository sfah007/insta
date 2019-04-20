<?php
function getMsgNewUser(){
	return  "این ربات برای ذخیره عکس پروفایل، عکس های پست شده و ویدیو های پست شده در اینستاگرام است.

لینک مورد نظر را کپی کرده و همینجا برای ربات ارسال نمایید. ربات در پاسخ عکس و یا فیلم را برایتان ارسال میکند.";
}
function getErrorMsg(){
	return  "خطا - لینک را چک نمایید ممکن است اشتباه باشد و یا پروفایل مورد نظر خصوصی باشد.";
}

function getNotValidUserReferenceErrorMessage(){
    return "Your Reference User is not valid, check the link or just skip that.";
}

function getAddPointNotificationToUserReferenceMessage(){
    return "You receive some point. Someone use your reference link to Start our BOT";
}

function getUserAlreadyExistMessage(){
    return "Your ID already exists in our BOT";
}
function getReferenceIdAlreadyExistMessage(){
    return "You already use This link.\nThis link is a disposable URL.";
}