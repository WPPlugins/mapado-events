jQuery(document).ready(function() {
  var $ = jQuery;

  function autocomplete($input, algoliaIndex, displayKey) {
    var _appId            ='I6TWPWMAOQ';
    var _searchOnlyApiKey = '6aaf79af978cb30623d034c8b58023e8';
    var algolia = algoliasearch(_appId, _searchOnlyApiKey);
    var index = algolia.initIndex(algoliaIndex);

    $input.hide();

    var $autocomplete = $('<input type="text" />');
    $autocomplete.val($input.attr('data-label'));
    $autocomplete.attr('placeholder', $input.attr('placeholder'));
    $input.after($autocomplete)
    $autocomplete.typeahead({hint: false}, {
      source: index.ttAdapter({hitsPerPage: 5}),
      displayKey: displayKey,
      templates: {
        suggestion: function(hit) {
          // render the hit
          return '<div class="hit">' +
            '<div class="name">' +
              hit._highlightResult[displayKey].value
            '</div>' +
          '</div>';
        }
      }
    });

    $autocomplete.on('typeahead:selected', function(ev, suggestion) {
      $input.val(suggestion['uuid']);
    });

    $autocomplete.on('change', function() {
      if (!this.value || this.value === '') {
        $input.val(null);
      } else {
        $input.val($(this).val());
      }
    });
  }

  autocomplete($('#search-filter-address'), 'address_tree', 'db_search_value');
  autocomplete($('#search-filter-rubric'), 'rubric_fr', 'name');
});
