#!/bin/bash

PARAMS=('-m 6 -q 80 -mt -af -progress')
for D in `find . -type d`
do
   # cd $D
    echo "Entered directory $D"
    cd $D

    # Define the image types to search for
    image_types=("*.jpeg" "*.jpg" "*.tiff" "*.tif" "*.png")

    # Iterate over each image type
    for type in "${image_types[@]}"; do
      # Find files of the specified image type
      find . -type f -iname "$type" | while read -r IMAGE; do
        # Get the filename without extension
       filename_without_extension=${IMAGE%.*}

        # Convert the image to WebP format
        cwebp $PARAMS "$IMAGE" -o "${filename_without_extension}.webp"

        echo "Converted $IMAGE to ${filename_without_extension}.webp"

         # rm -rf "$IMAGE";
      done
    done

done

echo "Conversion complete."