/**
 *
 */
jQuery(function($){
	// The column count
	var iCol = $('.price-columns .column').not('.addnew').length;
	/**
	 * Insert clearing divs in the price columns to put them into neat rows.
	 */
	resetColumns = function(){
	};
	var addPriceCol = function(){
		var thisICol = iCol++;
		var column = $('#column-skeleton').clone().attr('id', null);
		// Set up the new column
		$('.column-title',column).attr('name', 'price_'+thisICol+'_title').val('');
		$('.column-price',column).attr('name', 'price_'+thisICol+'_price').val('');
		$('.column-detail',column).attr('name', 'price_'+thisICol+'_detail').val('');
		$('.column-fine',column).attr('name', 'price_'+thisICol+'_fine').val('');
		$('.type input', column).attr({
			id : 'price_recommend_'+thisICol,
			value : thisICol
		});
		$('.type label', column).attr('for', 'price_recommend_'+thisICol);
		// And set up the first feature
		column.css('display', 'block').addClass('column').insertBefore('.price-columns .addnew');
		// Remove the placeholder column
		$('.feature', column).remove();
		// Make the pricetable features sortable
		column.find('.feautres').sortable({
			'items' : '.feature',
			'handle' : '.feature-handle',
			'stop' : resetColumns,
			'opacity' : 0.6
		});
		// Column deletion
		column.find('> a.deletion').click(function(){
			if(confirm(pt_messages.delete_column)){
				column.remove();
				resetColumns();
			}
			return false;
		});
		$('a.addfeature', column).click(function(){
			var featureCount = $('.feautres .feature', column).length;
			var feature = $('#column-skeleton .feature').last().clone().appendTo($('.feautres', column));
			feature.find('.feature-title').attr('name', 'price_'+thisICol+'_feature_'+featureCount+'_title');
			feature.find('.feature-sub').attr('name', 'price_'+thisICol+'_feature_'+featureCount+'_sub');
			feature.find('.feature-icon').attr('name', 'price_'+thisICol+'_feature_'+featureCount+'_icon');
			feature.find('.feature-description').attr('name', 'price_'+thisICol+'_feature_'+featureCount+'_description').elastic();
			feature.find('> a.deletion').click(function(){
				if(confirm(pt_messages.delete_feature)){
					feature.slideUp('normal',feature.remove());
					resetColumns();
				}
				return false;
			});
			// Reset columns to trigger the drag and drop
			resetColumns();
			return false;
		}).click();
		column.find('input').placeholder();
		// Reset the positions of the columns
		resetColumns();
	}
	$('.price-columns .addnew').click(function(){
		addPriceCol();
		return false;
	});
	// The price column sortable
	$('.price-columns').sortable({
		'items' : '.column:not(.addnew)',
		'handle' : '.column-handle',
		'stop' : resetColumns,
		'opacity' : 0.6
	});
	// Set up the existing price columns
	$('.price-columns .column').not('.addnew').each(function(i, el){
		var thisICol = i;
		var column = $(el);
		// Column deletion
		column.find('> a.deletion').click(function(){
			if(confirm(pt_messages.delete_column)){
				column.remove();
				resetColumns();
			}
			return false;
		});
		column.find('a.addfeature').click(function(){
			var featureCount = $('.feautres .feature', column).length;
			var feature = $('#column-skeleton .feature').last().clone().appendTo($('.feautres', column));
			feature.find('.feature-title').attr('name', 'price_'+thisICol+'_feature_'+featureCount+'_title');
			feature.find('.feature-sub').attr('name', 'price_'+thisICol+'_feature_'+featureCount+'_sub');
			feature.find('.feature-icon').attr('name', 'price_'+thisICol+'_feature_'+featureCount+'_icon');
			feature.find('.feature-description').attr('name', 'price_'+thisICol+'_feature_'+featureCount+'_description');
			feature.find('> a.deletion').click(function(){
				if(confirm(pt_messages.delete_feature)){
					feature.slideUp('normal',feature.remove());
					resetColumns();
				}
				return false;
			});
			// Set up auto resizing
			return false;
		});
		column.find('input').placeholder();
		// Make the pricetable elements sortable
		column.find('.feautres').sortable({
			'items' : '.feature',
			'handle' : '.feature-handle',
			'stop' : resetColumns,
			'opacity' : 0.6
		});
		column.find('.feature').each(function(){
			var feature = $(this);
			feature.find('> a.deletion').click(function(){
				if(confirm(pt_messages.delete_feature)){
					feature.slideUp('normal',feature.remove());
					resetColumns();
				}
				return false;
			});
		});
	});
    // Make the recommended price table uncheckable
    $('.price-columns input[name="price_recommend"]')
        .change(function(){
            $(this).data('changed', true);
        })
        .click(function(){
            var $$ = $(this);
            var changed = $$.data('changed');
            if((changed == undefined || changed == false) && $$.is(':checked')){
                $$.prop('checked', false);
            }
            $$.data('changed', false);
        });
	// Set up auto resizing on existing textareas
	$('.price-columns textarea.autoresize').not('#column-skeleton textarea.autoresize').elastic().trigger('change');
	// Reset the columns to start
	resetColumns();
	$(window).resize(resetColumns);
});