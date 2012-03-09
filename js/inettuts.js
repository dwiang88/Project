/*
 * Script from NETTUTS.com [by James Padolsey]
 * @requires jQuery($), jQuery UI & sortable/draggable UI modules
 */
var iNettuts = {
    
    jQuery : $,
    
    settings : {
        columns : '.column',
        widgetSelector: '.widget',
        handleSelector: '.widget-head',
        contentSelector: '.widget-content',
        widgetDefault : {
            movable: true,
            removable: true,
            collapsible: false,
            editable: false,
            colorClasses : ['color-yellow', 'color-red', 'color-blue', 'color-white', 'color-orange', 'color-green']
        },
        widgetIndividual : {
            intro : {
                movable: false,
                removable: false,
                collapsible: false,
                editable: false
            },
            projects : {
                movable: true,
                removable: false,
                collapsible: false,
                editable: false
            },
			
			messages : {
                movable: true,
                removable: false,
                collapsible: false,
                editable: false
            },
            mytasks : {
                movable: true,
                removable: false,
                collapsible: false,
                editable: false
            }
        }
    },
    init : function () {
        this.attachStylesheet('js/inettuts.js.css');
        this.addWidgetControls();
        this.makeSortable();
    },
    
    getWidgetSettings : function (id) {
        var $ = this.jQuery,
            settings = this.settings;
        return (id&&settings.widgetIndividual[id]) ? $.extend({},settings.widgetDefault,settings.widgetIndividual[id]) : settings.widgetDefault;
    },
    
    addWidgetControls : function () {
        var iNettuts = this,
            $ = this.jQuery,
            settings = this.settings;
            
        $(settings.widgetSelector, $(settings.columns)).each(function () {
            var thisWidgetSettings = iNettuts.getWidgetSettings(this.id);
            
            if (thisWidgetSettings.removable) {
                $('<a href="#" class="close"><img src="images/close.gif"/></a>').mousedown(function (e) {
                    e.stopPropagation();    
                }).click(function () {
                    if(confirm('This widget will be removed, ok?')) {
                    	
                    	data = 'id=' +  $(this).parents(settings.widgetSelector).attr('title');
                        alert(data);
                        $.get("includes/deletewidget.php", data);
                        $(this).parents(settings.widgetSelector).animate({
                            opacity: 0    
                        },function () {
                            $(this).wrap('<div/>').parent().slideUp(function () {
                                $(this).remove();
                                
                            });
                        });
                    }
                    return false;
                }).appendTo($(settings.handleSelector, this));
            }
            
//            if (thisWidgetSettings.editable) {
//                $('<a href="#" class="edit">EDIT</a>').mousedown(function (e) {
//                    e.stopPropagation();    
//                }).toggle(function () {
//                    $(this).css({backgroundPosition: '-66px 0', width: '55px'})
//                        .parents(settings.widgetSelector)
//                            .find('.edit-box').show().find('input').focus();
//                    return false;
//                },function () {
//                    $(this).css({backgroundPosition: '', width: ''})
//                        .parents(settings.widgetSelector)
//                            .find('.edit-box').hide();
//                    return false;
//                }).appendTo($(settings.handleSelector,this));
//                $('<div class="edit-box" style="display:none;"/>')
//                    .append('<ul><li class="item"><label>Change the title?</label><input value="' + $('h3',this).text() + '"/></li>')
//                    .append((function(){
//                        var colorList = '<li class="item"><label>Available colors:</label><ul class="colors">';
//                        $(thisWidgetSettings.colorClasses).each(function () {
//                            colorList += '<li class="' + this + '"/>';
//                        });
//                        return colorList + '</ul>';
//                    })())
//                    .append('</ul>')
//                    .insertAfter($(settings.handleSelector,this));
//            }
//            
//            if (thisWidgetSettings.collapsible) {
//                $('<a href="#" class="collapse">COLLAPSE</a>').mousedown(function (e) {
//                    e.stopPropagation();    
//                }).toggle(function () {
//                    $(this).css({backgroundPosition: '-38px 0'})
//                        .parents(settings.widgetSelector)
//                            .find(settings.contentSelector).hide();
//                    return false;
//                },function () {
//                    $(this).css({backgroundPosition: ''})
//                        .parents(settings.widgetSelector)
//                            .find(settings.contentSelector).show();
//                    return false;
//                }).prependTo($(settings.handleSelector,this));
//            }
        });
        
//        $('.edit-box').each(function () {
//            $('input',this).keyup(function () {
//                $(this).parents(settings.widgetSelector).find('h3').text( $(this).val().length>20 ? $(this).val().substr(0,20)+'...' : $(this).val() );
//            });
//            $('ul.colors li',this).click(function () {
//                
//                var colorStylePattern = /\bcolor-[\w]{1,}\b/,
//                    thisWidgetColorClass = $(this).parents(settings.widgetSelector).attr('class').match(colorStylePattern)
//                if (thisWidgetColorClass) {
//                    $(this).parents(settings.widgetSelector)
//                        .removeClass(thisWidgetColorClass[0])
//                        .addClass($(this).attr('class').match(colorStylePattern)[0]);
//                }
//                return false;
//                
//            });
//        });
        
    },
    
    attachStylesheet : function (href) {
        var $ = this.jQuery;
        return $('<link href="' + href + '" rel="stylesheet" type="text/css" />').appendTo('head');
    },
    
    makeSortable : function () {
        var iNettuts = this,
            $ = this.jQuery,
            settings = this.settings,
            $sortableItems = (function () {
                var notSortable = '';
                $(settings.widgetSelector,$(settings.columns)).each(function (i) {
                    if (!iNettuts.getWidgetSettings(this.id).movable) {
                        if(!this.id) {
                            this.id = 'widget-no-id-' + i;
                        }
                        notSortable += '#' + this.id + ',';
                    }
                });
                return $('> li:not(' + notSortable + ')', settings.columns);
            })();
        
        $sortableItems.find(settings.handleSelector).css({
            cursor: 'move'
        }).mousedown(function (e) {
            $sortableItems.css({width:''});
            $(this).parent().css({
                width: $(this).parent().width() + 'px'
            });
        }).mouseup(function () {
            if(!$(this).parent().hasClass('dragging')) {
                $(this).parent().css({width:''});
            } else {
                $(settings.columns).sortable('disable');
            }
        });
        $(settings.columns).sortable({
            items: $sortableItems,
            connectWith: $(settings.columns),
            handle: settings.handleSelector,
            placeholder: 'widget-placeholder',
            forcePlaceholderSize: true,
            revert: 300,
            delay: 100,
            opacity: 0.8,
            containment: 'document',
            start: function (e,ui) {
                $(ui.helper).addClass('dragging');
            },
            stop: function (e,ui) {
                $(ui.item).css({width:''}).removeClass('dragging');
                $(settings.columns).sortable('enable');
            },
            update: function() {
                iNettuts.savePreferences();
            }
        });
    },
    
    savePreferences : function () {
		
        var iNettuts = this,
            $ = this.jQuery,
            settings = this.settings,
            data = '';
        
        $(settings.columns).each(function(i){
        	var col = i;
            $(settings.widgetSelector,this).each(function(i){
            	data += '&' + $(this).attr('id') + '[id]=' + $(this).attr('title');
            	data += '&' + $(this).attr('id') + '[col]=' + col;
            	data += '&' + $(this).attr('id') + '[sort_order]=' + i; 
            });
            
        });
        //alert(data);
        $.get("includes/updatewidgets.php", data);
        
    }
  
};
iNettuts.init();