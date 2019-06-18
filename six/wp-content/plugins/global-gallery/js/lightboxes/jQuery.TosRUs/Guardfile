# Guardfile for the jQuery TosRUs plugin javascript files.
# This minifies the non-minified file in the "src/js" directory and concatenates them into one file.


# For some reason, uglify only seems to work if the input and output is the same file.
# Therefor, we need to copy the contents from the original files to the minified files (using concat) before they can be minified.

# Core
guard :concat, type: "js", files: %w(jquery.tosrus), input_dir: "src/js", output: "src/js/jquery.tosrus.min"

# Addons
guard :concat, type: "js", files: %w(jquery.tosrus.autoplay), input_dir: "src/js/addons", output: "src/js/addons/jquery.tosrus.autoplay.min"
guard :concat, type: "js", files: %w(jquery.tosrus.buttons), input_dir: "src/js/addons", output: "src/js/addons/jquery.tosrus.buttons.min"
guard :concat, type: "js", files: %w(jquery.tosrus.caption), input_dir: "src/js/addons", output: "src/js/addons/jquery.tosrus.caption.min"
guard :concat, type: "js", files: %w(jquery.tosrus.drag), input_dir: "src/js/addons", output: "src/js/addons/jquery.tosrus.drag.min"
guard :concat, type: "js", files: %w(jquery.tosrus.keys), input_dir: "src/js/addons", output: "src/js/addons/jquery.tosrus.keys.min"
guard :concat, type: "js", files: %w(jquery.tosrus.pagination), input_dir: "src/js/addons", output: "src/js/addons/jquery.tosrus.pagination.min"

# Media
guard :concat, type: "js", files: %w(jquery.tosrus.html), input_dir: "src/js/media", output: "src/js/media/jquery.tosrus.html.min"
guard :concat, type: "js", files: %w(jquery.tosrus.image), input_dir: "src/js/media", output: "src/js/media/jquery.tosrus.image.min"
guard :concat, type: "js", files: %w(jquery.tosrus.vimeo), input_dir: "src/js/media", output: "src/js/media/jquery.tosrus.vimeo.min"
guard :concat, type: "js", files: %w(jquery.tosrus.youtube), input_dir: "src/js/media", output: "src/js/media/jquery.tosrus.youtube.min"


# Minify the files seporately.

# Core
guard 'uglify', :destination_file => "src/js/jquery.tosrus.min.js" do
  watch ('src/js/jquery.tosrus.min.js')
end

# Addons
guard 'uglify', :destination_file => "src/js/addons/jquery.tosrus.autoplay.min.js" do
  watch ('src/js/addons/jquery.tosrus.autoplay.min.js')
end
guard 'uglify', :destination_file => "src/js/addons/jquery.tosrus.buttons.min.js" do
  watch ('src/js/addons/jquery.tosrus.buttons.min.js')
end
guard 'uglify', :destination_file => "src/js/addons/jquery.tosrus.caption.min.js" do
  watch ('src/js/addons/jquery.tosrus.caption.min.js')
end
guard 'uglify', :destination_file => "src/js/addons/jquery.tosrus.drag.min.js" do
  watch ('src/js/addons/jquery.tosrus.drag.min.js')
end
guard 'uglify', :destination_file => "src/js/addons/jquery.tosrus.keys.min.js" do
  watch ('src/js/addons/jquery.tosrus.keys.min.js')
end
guard 'uglify', :destination_file => "src/js/addons/jquery.tosrus.pagination.min.js" do
  watch ('src/js/addons/jquery.tosrus.pagination.min.js')
end

# Media
guard 'uglify', :destination_file => "src/js/media/jquery.tosrus.html.min.js" do
  watch ('src/js/media/jquery.tosrus.html.min.js')
end
guard 'uglify', :destination_file => "src/js/media/jquery.tosrus.image.min.js" do
  watch ('src/js/media/jquery.tosrus.image.min.js')
end
guard 'uglify', :destination_file => "src/js/media/jquery.tosrus.vimeo.min.js" do
  watch ('src/js/media/jquery.tosrus.vimeo.min.js')
end
guard 'uglify', :destination_file => "src/js/media/jquery.tosrus.youtube.min.js" do
  watch ('src/js/media/jquery.tosrus.youtube.min.js')
end

# Concatenate all minified js files into one
guard :concat, type: "js", files: %w(jquery.tosrus.min addons/jquery.tosrus.autoplay.min addons/jquery.tosrus.buttons.min addons/jquery.tosrus.caption.min addons/jquery.tosrus.drag.min addons/jquery.tosrus.keys.min addons/jquery.tosrus.pagination.min media/jquery.tosrus.html.min media/jquery.tosrus.image.min media/jquery.tosrus.vimeo.min media/jquery.tosrus.youtube.min), input_dir: "src/js", output: "src/js/jquery.tosrus.min.all"