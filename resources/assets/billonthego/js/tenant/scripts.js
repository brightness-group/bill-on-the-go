(function(window, undefined) {
    'use strict';

    if (typeof $ !== 'undefined') {
        $(function () {
            // Navbar Search with autosuggest (typeahead)
            // ? You can remove the following JS if you don't want to use search functionality.
            //----------------------------------------------------------------------------------

            var searchToggler = $('.search-toggler'),
                searchInputWrapper = $('.search-input-wrapper'),
                searchInput = $('.search-input'),
                contentBackdrop = $('.content-backdrop');

            // Open search input on click of search icon
            if (searchToggler.length) {
                searchToggler.on('click', function () {
                    if (searchInputWrapper.length) {
                        searchInputWrapper.toggleClass('d-none');
                        searchInput.trigger('focus');
                    }
                });
            }

            // Open search on 'CTRL+SHIFT+F'
            $(document).on('keydown', function (event) {
                if (event.defaultPrevented) {
                    return;
                }

                let ctrlKey  = event.ctrlKey,
                    shiftKey = event.shiftKey,
                    handle   = false;

                if (event.key !== undefined) {
                    handle = (event.key == 'F');
                } else if (event.keyCode !== undefined) {
                    handle = (event.keyCode === 114 || event.keyCode === 70);
                }

                if (/(Mac)/i.test(navigator.platform)) {
                    ctrlKey = event.metaKey;
                }

                if (ctrlKey && shiftKey && handle) {
                    if (searchInputWrapper.length) {
                        searchInputWrapper.toggleClass('d-none');
                        searchInput.trigger('focus');
                    }
                }

                // Escape mode
                if(event.key == 'Escape' && searchToggler.length){
                    $('.search-toggler.cursor-pointer').trigger('click');
                }
            });

            $(document).on('click','.search-toggler.cursor-pointer',function (){
                searchInput.typeahead('val','');
                if ($('.tt-input').data('current-route-name') == $('.tt-input').data('ao-route')) {
                    var uri = window.location.toString();
                    if (uri.indexOf("?") > 0) {
                        var clean_uri = uri.substring(0, uri.indexOf("?"));
                        window.history.replaceState({}, document.title, clean_uri);
                    }
                    if($(document).find("#search_term").val()){
                        $(document).find("#search_term").val('');
                    }
                    $(document).find("#search_term").trigger('keypress');
                }
            });

            // Todo: Add container-xxl to twitter-typeahead
            searchInput.on('focus', function () {
                if (searchInputWrapper.hasClass('container-xxl')) {
                    searchInputWrapper.find('.twitter-typeahead').addClass('container-xxl');
                }
            });

            if (searchInput.length) {
                // Search JSON
                var ajaxUrl = searchInput.data('url'),
                    timeoutID = null,
                    ajaxRequest = null;

                var searchOnPageAO = function(term) {
                    $(document).find("#search_term").val(term);

                    $(document).find("#search_term").trigger('keypress');

                    appendQueryStrings('gs', term);
                };

                var appendQueryStrings = function(key, value) {
                    const url = new URL(window.location);

                    url.searchParams.set(key, value);

                    window.history.pushState(null, '', url.toString());
                }

                // Filter config
                var filterConfig = function (data) {
                    return function findMatches(q, cb) {
                        let matches;
                        matches = [];
                        data.filter(function (i) {
                            if (i.name && i.name.toLowerCase().startsWith(q.toLowerCase())) {
                                matches.push(i);
                            } else {
                                matches.push(i);
                                matches.sort(function (a, b) {
                                    return b.name < a.name ? 1 : -1;
                                });
                            }
                        });
                        cb(matches);
                    };
                };

                var typeaheadInit = function(response) {
                    // Init typeahead on searchInput
                    var $this = $(this);

                    searchInput.typeahead("destroy");

                    searchInput
                    .typeahead(
                        {
                            hint: true,
                            highlight: true,
                            minLength: 1,
                            classNames: {
                                menu: 'tt-menu navbar-search-suggestion',
                                cursor: 'active',
                                suggestion: 'suggestion d-flex justify-content-between px-3 py-2 w-100'
                            }
                        },
                        // AO Page
                        {
                            name: 'ao',
                            display: 'term',
                            limit: 10,
                            source: filterConfig(response.ao),
                            templates: {
                                header: '<h6 class="suggestions-header text-primary mb-0 mx-3 mt-3 pb-2">' + response.lang.menu.activity_overview + '</h6>',
                                suggestion: function ({ name, url, notes }) {
                                    notes = notes ? notes.trim() : '';
                                    if (response.isAORoute) {
                                        return (
                                            '<a href="javascript:void(0);">' +
                                                '<div>' +
                                                    '<span>' +
                                                        name + ' <i>(' + response.lang.job_description + ' : ' + notes + ')</i>' +
                                                    '</span>' +
                                                '</div>' +
                                            '</a>'
                                        );
                                    } else {
                                        return (
                                            '<a href="' + url + '">' +
                                                '<div>' +
                                                    '<span>' +
                                                        name + ' <i>(' + response.lang.job_description + ' : ' + notes + ')</i>' +
                                                    '</span>' +
                                                '</div>' +
                                            '</a>'
                                        );
                                    }
                                },
                                notFound:
                                    '<div class="not-found px-3 py-2">' +
                                        '<h6 class="suggestions-header text-primary mb-2">' + response.lang.menu.activity_overview + '</h6>' +
                                        '<p class="py-2 mb-0"><i class="bx bx-error-circle bx-xs me-2"></i> ' + response.lang.no_records_msg + '</p>' +
                                    '</div>'
                            }
                        },
                        // Customers
                        {
                            name: 'customers',
                            display: 'name',
                            limit: 10,
                            source: filterConfig(response.customers),
                            templates: {
                                header: '<h6 class="suggestions-header text-primary mb-0 mx-3 mt-3 pb-2">' + response.lang.menu.customers + '</h6>',
                                suggestion: function ({ name, url }) {
                                    return (
                                        '<a href="' + url + '">' +
                                            '<div>' +
                                                '<span class="align-middle">' +
                                                    name +
                                                '</span>' +
                                            '</div>' +
                                        '</a>'
                                    );
                                },
                                notFound:
                                    '<div class="not-found px-3 py-2">' +
                                        '<h6 class="suggestions-header text-primary mb-2">' + response.lang.menu.customers + '</h6>' +
                                        '<p class="py-2 mb-0"><i class="bx bx-error-circle bx-xs me-2"></i> ' + response.lang.no_records_msg + '</p>' +
                                    '</div>'
                            }
                        },
                        // Users
                        {
                            name: 'users',
                            display: 'name',
                            limit: 10,
                            source: filterConfig(response.users),
                            templates: {
                                header: '<h6 class="suggestions-header text-primary mb-0 mx-3 mt-3 pb-2">' + response.lang.menu.users + '</h6>',
                                suggestion: function ({ name, url }) {
                                    return (
                                        '<a href="' + url + '">' +
                                            '<div>' +
                                                '<span class="align-middle">' +
                                                    name +
                                                '</span>' +
                                            '</div>' +
                                        '</a>'
                                    );
                                },
                                notFound:
                                    '<div class="not-found px-3 py-2">' +
                                        '<h6 class="suggestions-header text-primary mb-2">' + response.lang.menu.users + '</h6>' +
                                        '<p class="py-2 mb-0"><i class="bx bx-error-circle bx-xs me-2"></i> ' + response.lang.no_records_msg + '</p>' +
                                    '</div>'
                            }
                        },
                        // Contacts
                        {
                            name: 'contacts',
                            display: 'name',
                            limit: 10,
                            source: filterConfig(response.contacts),
                            templates: {
                                header: '<h6 class="suggestions-header text-primary mb-0 mx-3 mt-3 pb-2">' + response.lang.menu.contacts + '</h6>',
                                suggestion: function ({ full_name, url, customer }) {
                                    let customerName = (customer && customer.customer_name ? customer.customer_name : false),
                                        groupId      = (customer && customer.bdgogid ? customer.bdgogid : false);

                                    if (customerName) {
                                        return (
                                            '<a href="' + url + '">' +
                                                '<div>' +
                                                    '<span>' +
                                                        full_name + ' <i>(' + response.lang.company + ' : ' + customerName + ')</i>' +
                                                    '</span>' +
                                                '</div>' +
                                            '</a>'
                                        );
                                    } else if (groupId) {
                                        return (
                                            '<a href="' + url + '">' +
                                                '<div>' +
                                                    '<span>' +
                                                        full_name + ' <i>(' + response.lang.group + ' : ' + groupId + ')</i>' +
                                                    '</span>' +
                                                '</div>' +
                                            '</a>'
                                        );
                                    } else {
                                        return (
                                            '<a href="' + url + '">' +
                                                '<div>' +
                                                    '<span class="align-middle">' +
                                                        full_name +
                                                    '</span>' +
                                                '</div>' +
                                            '</a>'
                                        );
                                    }
                                },
                                notFound:
                                    '<div class="not-found px-3 py-2">' +
                                        '<h6 class="suggestions-header text-primary mb-2">' + response.lang.menu.contacts + '</h6>' +
                                        '<p class="py-2 mb-0"><i class="bx bx-error-circle bx-xs me-2"></i> ' + response.lang.no_records_msg + '</p>' +
                                    '</div>'
                            }
                        }
                    )
                    //On typeahead result render.
                    .trigger('bind', 'typeahead:render', function () {
                        // Show content backdrop,
                        contentBackdrop.addClass('show').removeClass('fade');
                    })
                    // On typeahead close
                    .trigger('bind', 'typeahead:close', function () {
                        // Clear search
                        searchInput.val('');
                        $this.typeahead('val', '');
                        // Hide search input wrapper
                        searchInputWrapper.addClass('d-none');
                        // Fade content backdrop
                        contentBackdrop.addClass('fade').removeClass('show');
                    });

                    // On typeahead option select
                    searchInput.on('typeahead:selected', function(event, selection) {
                        if (selection.url && selection.url.length > 0) {
                            window.location.href = selection.url;
                        }
                    });

                    searchInput.trigger('focus');

                    initPerfectScrollbar();

                    if (response.isAORoute) {
                        searchOnPageAO(response.term);
                    }
                };

                var ajaxGetSearch = function() {
                    let currentRouteName = searchInput.data('current-route-name'),
                        aoRoute = searchInput.data('ao-route');

                    $.ajaxSetup({
                        async: false
                    });

                    if (searchInput.val().length >= 2) {
                        if (ajaxRequest) {
                            ajaxRequest.abort();
                        }

                        ajaxRequest = $.getJSON(ajaxUrl + "?term=" + searchInput.val() + "&current-route-name=" + currentRouteName).done(function(response) {
                            typeaheadInit(response);
                        });

                        return ajaxRequest;
                    } else if (currentRouteName == aoRoute) {
                        searchOnPageAO(searchInput.val());
                    }
                };

                var initPerfectScrollbar = function() {
                    // Init PerfectScrollbar in search result
                    $('.navbar-search-suggestion').each(function () {
                        new PerfectScrollbar($(this)[0], {
                            wheelPropagation: false,
                            suppressScrollX: true
                        });
                    });
                };

                // Search API AJAX call
                searchInput.unbind().on('keyup', function (event) {
                    let keyCode = event.keyCode || event.which;

                    // Don't validate the input if below arrow, delete and backspace keys were pressed
                    if (keyCode == 8 || (keyCode >= 35 && keyCode <= 40)) {
                        // Left / Up / Right / Down Arrow, Backspace, Delete keys
                        return;
                    }

                    clearTimeout(timeoutID);

                    timeoutID = setTimeout(function() { ajaxGetSearch(); }, 500);

                    if ((searchInput.val() == '')) {
                        contentBackdrop.addClass('fade').removeClass('show');
                    }
                });

                searchInput.on('focus', function () {
                    // hide tt-menu
                    if ((searchInput.data('ao-route') == searchInput.data('current-route-name'))) {
                        if (!$('.tt-menu').hasClass('d-none')) {
                            $('.tt-menu').addClass('d-none');
                        }
                    }
                });

                searchInput.typeahead();
            }
        });

    }
})(window);
