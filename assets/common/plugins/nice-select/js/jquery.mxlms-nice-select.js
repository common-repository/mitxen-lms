/*  jQuery Nice Select - v1.1.0
    https://github.com/hernansartorio/jquery-nice-select
    Made by Hernán Sartorio  */
 
(function($) {

  $.fn.niceSelect = function(method) {
    
    // Methods
    if (typeof method == 'string') {      
      if (method == 'update') {
        this.each(function() {
          var $select = $(this);
          var $dropdown = $(this).next('.mxlms-nice-select');
          var open = $dropdown.hasClass('mxlms-open');
          
          if ($dropdown.length) {
            $dropdown.remove();
            create_nice_select($select);
            
            if (open) {
              $select.next().trigger('click');
            }
          }
        });
      } else if (method == 'destroy') {
        this.each(function() {
          var $select = $(this);
          var $dropdown = $(this).next('.mxlms-nice-select');
          
          if ($dropdown.length) {
            $dropdown.remove();
            $select.css('display', '');
          }
        });
        if ($('.mxlms-nice-select').length == 0) {
          $(document).off('.mxlms-nice_select');
        }
      } else {
        console.log('Method "' + method + '" does not exist.')
      }
      return this;
    }
      
    // Hide native select
    this.hide();
    
    // Create custom markup
    this.each(function() {
      var $select = $(this);
      
      if (!$select.next().hasClass('mxlms-nice-select')) {
        create_nice_select($select);
      }
    });
    
    function create_nice_select($select) {
      $select.after($('<div></div>')
        .addClass('mxlms-nice-select')
        .addClass($select.attr('class') || '')
        .addClass($select.attr('disabled') ? 'disabled' : '')
        .attr('tabindex', $select.attr('disabled') ? null : '0')
        .html('<span class="mxlms-current"></span><ul class="mxlms-list"></ul>')
      );
        
      var $dropdown = $select.next();
      var $options = $select.find('option');
      var $selected = $select.find('option:selected');
      
      $dropdown.find('.mxlms-current').html($selected.data('display') || $selected.text());
      
      $options.each(function(i) {
        var $option = $(this);
        var display = $option.data('display');

        $dropdown.find('ul').append($('<li></li>')
          .attr('data-value', $option.val())
          .attr('data-display', (display || null))
          .addClass('mxlms-option' +
            ($option.is(':selected') ? ' selected' : '') +
            ($option.is(':disabled') ? ' disabled' : ''))
          .html($option.text())
        );
      });
    }
    
    /* Event listeners */
    
    // Unbind existing events in case that the plugin has been initialized before
    $(document).off('.mxlms-nice_select');
    
    // Open/close
    $(document).on('click.mxlms-nice_select', '.mxlms-nice-select', function(event) {
      var $dropdown = $(this);
      
      $('.mxlms-nice-select').not($dropdown).removeClass('mxlms-open');
      $dropdown.toggleClass('mxlms-open');
      
      if ($dropdown.hasClass('mxlms-open')) {
        $dropdown.find('.mxlms-option');  
        $dropdown.find('.mxlms-focus').removeClass('mxlms-focus');
        $dropdown.find('.mxlms-selected').addClass('mxlms-focus');
      } else {
        $dropdown.focus();
      }
    });
    
    // Close when clicking outside
    $(document).on('click.mxlms-nice_select', function(event) {
      if ($(event.target).closest('.mxlms-nice-select').length === 0) {
        $('.mxlms-nice-select').removeClass('mxlms-open').find('.mxlms-option');  
      }
    });
    
    // Option click
    $(document).on('click.mxlms-nice_select', '.mxlms-nice-select .mxlms-option:not(.mxlms-disabled)', function(event) {
      var $option = $(this);
      var $dropdown = $option.closest('.mxlms-nice-select');
      
      $dropdown.find('.mxlms-selected').removeClass('mxlms-selected');
      $option.addClass('mxlms-selected');
      
      var text = $option.data('display') || $option.text();
      $dropdown.find('.mxlms-current').text(text);
      
      $dropdown.prev('select').val($option.data('value')).trigger('change');
    });

    // Keyboard events
    $(document).on('keydown.mxlms-nice_select', '.mxlms-nice-select', function(event) {    
      var $dropdown = $(this);
      var $focused_option = $($dropdown.find('.mxlms-focus') || $dropdown.find('.mxlms-list .mxlms-option.mxlms-selected'));
      
      // Space or Enter
      if (event.keyCode == 32 || event.keyCode == 13) {
        if ($dropdown.hasClass('mxlms-open')) {
          $focused_option.trigger('click');
        } else {
          $dropdown.trigger('click');
        }
        return false;
      // Down
      } else if (event.keyCode == 40) {
        if (!$dropdown.hasClass('mxlms-open')) {
          $dropdown.trigger('mxlms-click');
        } else {
          var $next = $focused_option.nextAll('.mxlms-option:not(.mxlms-disabled)').first();
          if ($next.length > 0) {
            $dropdown.find('.mxlms-focus').removeClass('mxlms-focus');
            $next.addClass('mxlms-focus');
          }
        }
        return false;
      // Up
      } else if (event.keyCode == 38) {
        if (!$dropdown.hasClass('mxlms-open')) {
          $dropdown.trigger('click');
        } else {
          var $prev = $focused_option.prevAll('.mxlms-option:not(.mxlms-disabled)').first();
          if ($prev.length > 0) {
            $dropdown.find('.mxlms-focus').removeClass('mxlms-focus');
            $prev.addClass('mxlms-focus');
          }
        }
        return false;
      // Esc
      } else if (event.keyCode == 27) {
        if ($dropdown.hasClass('mxlms-open')) {
          $dropdown.trigger('click');
        }
      // Tab
      } else if (event.keyCode == 9) {
        if ($dropdown.hasClass('mxlms-open')) {
          return false;
        }
      }
    });

    // Detect CSS pointer-events support, for IE <= 10. From Modernizr.
    var style = document.createElement('a').style;
    style.cssText = 'pointer-events:auto';
    if (style.pointerEvents !== 'auto') {
      $('html').addClass('mxlms-no-csspointerevents');
    }
    
    return this;

  };

}(jQuery));