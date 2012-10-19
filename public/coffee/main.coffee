window.Rfpez ||= {}

Rfpez.current_page = (str) ->
  if str is Rfpez.current_page_string
    true
  else
    false

$(document).on 'shown', '#signinModal', ->
  $("#signinModal #email").focus()

$(document).on "click", "a[data-confirm]", (e) ->
  e.preventDefault();
  el = $(this)
  if confirm(el.data('confirm'))
    window.location = el.attr('href')

$(document).on "submit", "#new-contract-form", (e) ->
  return e.preventDefault() unless $(this).find('input[name=solnbr]').val()
  $(this).find("button[type=submit]").button('loading')

$(document).on "click", "[data-select-text-on-focus]", (e) ->
  $(this).select()

$(document).on "click", "a[data-pjax]", (e) ->
  $.pjax.click e, "#pjax-container"

$(document).on "ready pjax:success", ->
  $("[data-onload-focus]:eq(0)").focus()
  $("span.timeago").timeago()

  Rfpez.current_page_string = $("#current-page").val()