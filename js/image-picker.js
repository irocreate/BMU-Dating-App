// Image Picker
// by Rodrigo Vera
//
// Version 0.1.5
// Full source at https://github.com/rvera/image-picker
// MIT License, https://github.com/rvera/image-picker/blob/master/LICENSE
// Generated by CoffeeScript 1.4.0
(function() {
    var ImagePicker, ImagePickerOption, both_array_are_equal, sanitized_options,
            __indexOf = [].indexOf || function(item) {
        for (var i = 0, l = this.length; i < l; i++) {
            if (i in this && this[i] === item)
                return i;
        }
        return -1;
    };
    jQuery.fn.extend({
        imagepicker: function(opts) {
            if (opts == null) {
                opts = {};
            }
            return this.each(function() {
                var select;
                select = jQuery(this);
                select.next("ul.image_picker_selector").remove();
                select.data("picker", new ImagePicker(this, sanitized_options(opts)));
                if (opts.initialized != null) {
                    return opts.initialized();
                }
            });
        }
    });
    sanitized_options = function(opts) {
        var default_options;
        default_options = {
            hide_select: true,
            show_label: false,
            initialized: void 0,
            changed: void 0,
            clicked: void 0,
            selected: void 0,
            limit: void 0,
            limit_reached: void 0
        };
        return jQuery.extend(default_options, opts);
    };
    both_array_are_equal = function(a, b) {
        return jQuery(a).not(b).length === 0 && jQuery(b).not(a).length === 0;
    };
    ImagePicker = (function() {
        function ImagePicker(select_element, opts) {
            this.opts = opts != null ? opts : {};
            this.select = jQuery(select_element);
            this.multiple = this.select.attr("multiple") === "multiple";
            if (this.select.data("limit") != null) {
                this.opts.limit = parseInt(this.select.data("limit"));
            }
            this.build_and_append_picker();
        }
        ImagePicker.prototype.build_and_append_picker = function() {
            if (this.opts.hide_select) {
                this.select.hide();
            }
            this.select.change({
                picker: this
            }, function(event) {
                return event.data.picker.sync_picker_with_select();
            });
            if (this.picker != null) {
                this.picker.remove();
            }
            this.create_picker();
            this.select.after(this.picker);
            return this.sync_picker_with_select();
        };
        ImagePicker.prototype.sync_picker_with_select = function() {
            var option, _i, _len, _ref, _results;
            _ref = this.picker_options;
            _results = [];
            for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                option = _ref[_i];
                if (option.is_selected()) {
                    _results.push(option.mark_as_selected());
                } else {
                    _results.push(option.unmark_as_selected());
                }
            }
            return _results;
        };
        ImagePicker.prototype.create_picker = function() {
            var option, _i, _len, _ref;
            this.picker = jQuery("<ul class='thumbnails image_picker_selector'></ul>");
            this.picker_options = (function() {
                var _i, _len, _ref, _results;
                _ref = this.select.find("option");
                _results = [];
                for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                    option = _ref[_i];
                    _results.push(new ImagePickerOption(option, this, this.opts));
                }
                return _results;
            }).call(this);
            _ref = this.picker_options;
            for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                option = _ref[_i];
                if (!option.has_image()) {
                    continue;
                }
                this.picker.append(option.node);
            }
            return this.picker;
        };
        ImagePicker.prototype.has_implicit_blanks = function() {
            var option;
            return ((function() {
                var _i, _len, _ref, _results;
                _ref = this.picker_options;
                _results = [];
                for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                    option = _ref[_i];
                    if (option.is_blank() && !option.has_image()) {
                        _results.push(option);
                    }
                }
                return _results;
            }).call(this)).length > 0;
        };
        ImagePicker.prototype.selected_values = function() {
            if (this.multiple) {
                return this.select.val() || [];
            } else {
                return [this.select.val()];
            }
        };
        ImagePicker.prototype.toggle = function(imagepicker_option) {
            var new_values, old_values, _ref;
            old_values = this.selected_values();
            if (this.multiple) {
                if (_ref = imagepicker_option.value(), __indexOf.call(this.selected_values(), _ref) >= 0) {
                    imagepicker_option.option.prop("selected", false);
                } else {
                    if (this.opts.limit != null) {
                        if (this.selected_values().length < this.opts.limit) {
                            imagepicker_option.option.prop("selected", true);
                        } else if (this.opts.limit_reached != null) {
                            this.opts.limit_reached.call(this.select);
                        }
                    } else {
                        imagepicker_option.option.prop("selected", true);
                    }
                }
            } else {
                if (this.has_implicit_blanks() && imagepicker_option.is_selected()) {
                    this.select.val("");
                } else {
                    this.select.val(imagepicker_option.value());
                }
            }
            new_values = this.selected_values();
            if (!both_array_are_equal(old_values, new_values)) {
                this.select.change();
                if (this.opts.changed != null) {
                    return this.opts.changed.call(this.select);
                }
            }
        };
        return ImagePicker;
    })();
    ImagePickerOption = (function() {
        function ImagePickerOption(option_element, picker, opts) {
            this.picker = picker;
            this.opts = opts != null ? opts : {};
            this.option = jQuery(option_element);
            this.create_node();
        }
        ImagePickerOption.prototype.has_image = function() {
            return this.option.data("img-src") != null;
        };
        ImagePickerOption.prototype.is_blank = function() {
            return !((this.value() != null) && this.value() !== "");
        };
        ImagePickerOption.prototype.is_selected = function() {
            var select_value;
            select_value = this.picker.select.val();
            if (this.picker.multiple) {
                return jQuery.inArray(this.value(), select_value) >= 0;
            } else {
                return this.value() === select_value;
            }
        };
        ImagePickerOption.prototype.mark_as_selected = function() {
            return this.node.find(".thumbnail").addClass("selected");
        };
        ImagePickerOption.prototype.unmark_as_selected = function() {
            return this.node.find(".thumbnail").removeClass("selected");
        };
        ImagePickerOption.prototype.value = function() {
            return this.option.val();
        };
        ImagePickerOption.prototype.label = function() {
            if (this.option.data("img-label")) {
                return this.option.data("img-label");
            } else {
                return this.option.text();
            }
        };
        ImagePickerOption.prototype.clicked = function() {
            this.picker.toggle(this);
            if (this.opts.clicked != null) {
                this.opts.clicked.call(this.picker.select);
            }
            if ((this.opts.selected != null) && this.is_selected()) {
                return this.opts.selected.call(this.picker.select);
            }
        };
        ImagePickerOption.prototype.create_node = function() {
            var image, thumbnail;
            this.node = jQuery("<li/>");
            image = jQuery("<img class='image_picker_image'/>");
            image.attr("src", this.option.data("img-src"));
            thumbnail = jQuery("<div class='thumbnail'>");
            thumbnail.click({
                option: this
            }, function(event) {
                return event.data.option.clicked();
            });
            thumbnail.append(image);
            if (this.opts.show_label) {
                thumbnail.append(jQuery("<p/>").html(this.label()));
            }
            this.node.append(thumbnail);
            return this.node;
        };
        return ImagePickerOption;
    })();
}).call(this);
