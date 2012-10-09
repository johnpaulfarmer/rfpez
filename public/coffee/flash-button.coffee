$ = jQuery

# Adds plugin object to jQuery
$.fn.extend
  # Change pluginName to your plugin's name.
  flash_button_message: (button_class, message, timeout) ->
    timeout ||= 1000

    return @each ->
      button = $(this)
      original_text = button.text()
      original_classes = button.attr('class')

      button.removeClass('btn-primary btn-info btn-success btn-warning btn-danger btn-inverse btn-link')
      button.addClass("btn-#{button_class}")
      button.text(message)

      setTimeout ->
        button.attr('class', original_classes)
        button.text(original_text)
      , timeout