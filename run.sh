#!/bin/bash

while true; do

	php /var/www/html/PostingTwitterApp/video2frame/video2frame.php
	php /var/www/html/PostingTwitterApp/frame2twitter/frame2twitter.php
	sleep 10m

done


