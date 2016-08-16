
MRISocialFeed = ->
  $ = jQuery
  $(document).on 'click', '.mri-tab-titles .mri-tab-title:not(.active)', (event) ->
    event.preventDefault()
    titles = $('.mri-tab-titles .mri-tab-title')
    contents = $('.mri-tab-contents .mri-tab-content')
    content = $('.mri-tab-contents .mri-tab-content[data-id='+$(this).data('id')+']')

    titles.not($(this)).removeClass('active')
    contents.not(content).removeClass('active')

    $(this).addClass('active')
    content.addClass('active')

  $(document).on 'click', '.mri-repeater-add-row-trigger', (event) ->
    event.preventDefault()
    table = $(this).closest('.mri-repeater')
    template = $(table.find('.template-row')[0].outerHTML)
    template.removeClass('template-row')
    timestamp = new Date().getTime()
    template.find('input').each ->
      scope = $(this).attr('data-scope')
      name = $(this).attr('data-name')
      $(this).attr('name', "#{scope}[#{timestamp}][#{name}]")
    table.append(template)

  $(document).on 'click', '.mri-repeater-remove-row-trigger', (event) ->
    event.preventDefault()
    $(this).closest('tr').remove()

MRISocialFeed()
