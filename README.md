# Test JPEG imagemagick settings

## Target
Find best options for ImageMagick JPEG conversion for:
1. Keep good enough quality for photos
2. Keep files smaller
3. Fit Google PageSpeed Insights requirements

## How it's done
Variating different settings we've got a different images. 
Size and images comparision matrix published at http://test-imagick-jpeg-settings.d4m.ru/

## Conclusion
1. Use settings ``-sampling-factor 4:2:0 -colorspace sRGB -interlace JPEG`` for JPEG images
2. Use high quality ``-quality 85`` for:
    - projects with attracting photos, like photographers portfolio etc
    - non-photography images like logotypes or icon sets
3. Use mid quality ``-quality 75`` for most of projects
4. Use low quality ``-quality 70`` for technical images like preview of images in admin panel

# Contributing
Any suggestions/corrections is welcome. Write me here.
