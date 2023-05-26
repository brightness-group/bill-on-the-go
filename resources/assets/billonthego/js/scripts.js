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
                    searchInput.typeahead('val','');
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

                // Filter config
                var filterConfig = function (data) {
                    return function findMatches(q, cb) {
                        let matches;
                        matches = [];
                        data.filter(function (i) {
                            if (i.name.toLowerCase().startsWith(q.toLowerCase())) {
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
                        // Companies
                        {
                            name: 'companies',
                            display: 'term',
                            limit: 10,
                            source: filterConfig(response.companies),
                            templates: {
                                header: '<h6 class="suggestions-header text-primary mb-0 mx-3 mt-3 pb-2">' + response.lang.menu.companies + '</h6>',
                                suggestion: function ({ url, name, subdomain, address, zip, city, country, email, contact, contact_email }) {
                                    let extraField = [];

                                    if (subdomain && subdomain !== undefined) {
                                        extraField.push(response.lang.subdomain + ' : ' + subdomain);
                                    }

                                    if (address && address !== undefined) {
                                        extraField.push(response.lang.address + ' : ' + address);
                                    }

                                    if (zip && zip !== undefined) {
                                        extraField.push(response.lang.zip + ' : ' + zip);
                                    }

                                    if (city && city !== undefined) {
                                        extraField.push(response.lang.city + ' : ' + city);
                                    }

                                    if (country && country !== undefined) {
                                        extraField.push(response.lang.country + ' : ' + country);
                                    }

                                    if (email && email !== undefined) {
                                        extraField.push(response.lang.email + ' : ' + email);
                                    }

                                    if (contact && contact !== undefined) {
                                        extraField.push(response.lang.contact + ' : ' + contact);
                                    }

                                    if (contact_email && contact_email !== undefined) {
                                        extraField.push(response.lang.contact_email + ' : ' + contact_email);
                                    }

                                    return (
                                        '<a href="' + url + '">' +
                                            '<div>' +
                                                '<span>' +
                                                    name + (extraField.length > 0 ? ' <i>(' + extraField.join(", ") + ')</i>' : '') +
                                                '</span>' +
                                            '</div>' +
                                        '</a>'
                                    );
                                },
                                notFound:
                                    '<div class="not-found px-3 py-2">' +
                                        '<h6 class="suggestions-header text-primary mb-2">' + response.lang.menu.companies + '</h6>' +
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
                };

                var ajaxGetSearch = function() {
                    $.ajaxSetup({
                        async: false
                    });

                    if (searchInput.val().length >= 2) {
                        if (ajaxRequest) {
                            ajaxRequest.abort();
                        }

                        ajaxRequest = $.getJSON(ajaxUrl + "?term=" + searchInput.val()).done(function(response) {
                            typeaheadInit(response);
                        });

                        return ajaxRequest;
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
                searchInput.unbind().on('keyup', function () {
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

                searchInput.typeahead();
            }
        });
    }
})(window);
