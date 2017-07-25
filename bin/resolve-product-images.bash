#!/bin/bash

IMAGE_PATH="web/assets/uploads/products/"
IMAGE_EXTS="jpeg JPEG jpg JPG png PNG"
FILTERS="product_lg product_thumb category_thumb product_thumb_sm product_thumb_no_border lightbox_focus"

for ext in ${IMAGE_EXTS}
do
  find . -iname "*.${ext}" | grep ${IMAGE_PATH} | while read file
  do
    filter_opts=""
    for filter in ${FILTERS}
    do
      filter_opts="${filter_opts}--filters=${filter} "
    done
    echo -en "RESOLVING \"${file:6}\"\n"
    bin/console liip:imagine:cache:resolve "${file:6}" ${filter_opts}
  done
done

