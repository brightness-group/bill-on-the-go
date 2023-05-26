/**
 * Main
 */

'use strict';

let isRtl = window.Helpers.isRtl(),
  isDarkStyle = window.Helpers.isDarkStyle(),
  menu,
  animate,
  isHorizontalLayout = false;

if (document.getElementById('layout-menu')) {
  isHorizontalLayout = document.getElementById('layout-menu').classList.contains('menu-horizontal');
}

// feather
//-----------------
feather.replace();

// Page full screen
//-----------------
$(".nav-link-expand").on("click", function (e) {
    if (typeof screenfull != "undefined") {
        if (screenfull.isEnabled) {
            screenfull.toggle();
            screenfull.on('change', () => {
                if (screenfull.isFullscreen) {
                    $(".nav-link-expand").html("<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"25\" height=\"25\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\" class=\"feather feather-minimize\"><path d=\"M8 3v3a2 2 0 0 1-2 2H3m18 0h-3a2 2 0 0 1-2-2V3m0 18v-3a2 2 0 0 1 2-2h3M3 16h3a2 2 0 0 1 2 2v3\"></path></svg>");
                } else {
                    $(".nav-link-expand").html("<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"25\" height=\"25\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\" class=\"feather feather-maximize\"><path d=\"M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3\"></path></svg>");
                }
            });
        }
    }
});

(function () {
  // Initialize menu
  //-----------------

  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  function toggleCompanyLogo(isFixed)
  {
    isFixed = isFixed || false;

    let logoFixed = document.getElementsByClassName('app-brand-logo fixed'),
        logoCollapsed = document.getElementsByClassName('app-brand-logo collapsed');

    if (window.Helpers.isCollapsed() && !isFixed) {
      if (logoCollapsed && logoCollapsed.length > 0) {
        logoCollapsed[0].classList.remove('d-none');
      }

      if (logoFixed && logoFixed.length > 0) {
        logoFixed[0].classList.add('d-none');
      }
    } else {
      if (logoFixed && logoFixed.length > 0) {
        logoFixed[0].classList.remove('d-none');
      }

      if (logoCollapsed && logoCollapsed.length > 0) {
        logoCollapsed[0].classList.add('d-none');
      }
    }
  }

  function setStyle(name, theme, minutes)
  {
    $.post('/ajax/setCookie', {name: name, value: theme, minutes: minutes}).done(function() {
      window.templateCustomizer.setStyle(theme);
    });
  }

  window.addEventListener("load", function() {
    toggleCompanyLogo();
  });

  // Check aside hover.
  $(document).find("aside#layout-menu").on("mouseover", function() {
    setTimeout(function() {
      let hasMenuHover = $(document).find("html.layout-navbar-fixed").hasClass("layout-menu-hover");
      
      toggleCompanyLogo(hasMenuHover);
    }, 500);
  });

  let layoutMenuEl = document.querySelectorAll('#layout-menu');
  layoutMenuEl.forEach(function (element) {
    menu = new Menu(element, {
      orientation: isHorizontalLayout ? 'horizontal' : 'vertical',
      closeChildren: isHorizontalLayout ? true : false,
      // ? This option only works with Horizontal menu
      showDropdownOnHover: localStorage.getItem('templateCustomizer-' + templateName + '--ShowDropdownOnHover') // If value(showDropdownOnHover) is set in local storage
        ? localStorage.getItem('templateCustomizer-' + templateName + '--ShowDropdownOnHover') === 'true' // Use the local storage value
        : window.templateCustomizer !== undefined // If value is set in config.js
        ? window.templateCustomizer.settings.defaultShowDropdownOnHover // Use the config.js value
        : true // Use this if you are not using the config.js and want to set value directly from here
    });
    // Change parameter to true if you want scroll animation
    window.Helpers.scrollToActive((animate = false));
    window.Helpers.mainMenu = menu;
  });

  // Initialize menu togglers and bind click on each
  let menuToggler = document.querySelectorAll('.layout-menu-toggle');
  menuToggler.forEach(item => {
    item.addEventListener('click', event => {
      event.preventDefault();
      window.Helpers.toggleCollapsed();
      // Enable menu state with local storage support if enableMenuLocalStorage = true from config.js
      if (config.enableMenuLocalStorage && !window.Helpers.isSmallScreen()) {
        try {
          localStorage.setItem(
            'templateCustomizer-' + templateName + '--LayoutCollapsed',
            String(window.Helpers.isCollapsed())
          );

          toggleCompanyLogo();
        } catch (e) {}
      }
    });
  });

  // Menu swipe gesture

  // Detect swipe gesture on the target element and call swipe In
  window.Helpers.swipeIn('.drag-target', function (e) {
    window.Helpers.setCollapsed(false);
  });

  // Detect swipe gesture on the target element and call swipe Out
  window.Helpers.swipeOut('#layout-menu', function (e) {
    if (window.Helpers.isSmallScreen()) window.Helpers.setCollapsed(true);
  });

  // Display in main menu when menu scrolls
  let menuInnerContainer = document.getElementsByClassName('menu-inner'),
    menuInnerShadow = document.getElementsByClassName('menu-inner-shadow')[0];
  if (menuInnerContainer.length > 0 && menuInnerShadow) {
    menuInnerContainer[0].addEventListener('ps-scroll-y', function () {
      if (this.querySelector('.ps__thumb-y').offsetTop) {
        menuInnerShadow.style.display = 'block';
      } else {
        menuInnerShadow.style.display = 'none';
      }
    });
  }

  // Style Switcher (Light/Dark Mode)
  //---------------------------------

  let styleSwitcherToggleEl = document.querySelector('.style-switcher-toggle');
  if (window.templateCustomizer) {
    // setStyle light/dark on click of styleSwitcherToggleEl
    if (styleSwitcherToggleEl) {
      styleSwitcherToggleEl.addEventListener('click', function () {
        if (window.Helpers.isLightStyle()) {
          setStyle('current-theme', 'dark', 2628000);
        } else {
          setStyle('current-theme', 'light', 2628000);
        }
      });
    }
    // Update style switcher icon and tooltip based on current style
    if (window.Helpers.isLightStyle()) {
      if (styleSwitcherToggleEl) {
        // styleSwitcherToggleEl.querySelector('i').classList.add('bx-moon');
          styleSwitcherToggleEl.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-moon"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>';
        new bootstrap.Tooltip(styleSwitcherToggleEl, {
          title: dark_mode,
          fallbackPlacements: ['bottom']
        });
      }
      switchImage('light');
    } else {
      if (styleSwitcherToggleEl) {
          styleSwitcherToggleEl.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-sun"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>';
        // styleSwitcherToggleEl.querySelector('i').classList.add('bx-sun');
        new bootstrap.Tooltip(styleSwitcherToggleEl, {
          title: light_mode,
          fallbackPlacements: ['bottom']
        });
      }
      switchImage('dark');
    }
  }

  // Update light/dark image based on current style
  function switchImage(style) {
    const switchImagesList = [].slice.call(document.querySelectorAll('[data-app-' + style + '-img]'));
    switchImagesList.map(function (imageEl) {
      const setImage = imageEl.getAttribute('data-app-' + style + '-img');
      imageEl.src = assetsPath + 'img/' + setImage; // Using window.assetsPath to get the exact relative path
    });
  }

  // Navbar Scroll class
  //---------------------
  function scrollTopFn() {
    if (document.getElementById('layout-navbar')) {
      if (document.body.scrollTop > 10 || document.documentElement.scrollTop > 10) {
        document.getElementById('layout-navbar').classList.add('navbar-elevated');
      } else {
        document.getElementById('layout-navbar').classList.remove('navbar-elevated');
      }
    }
  }
  window.onscroll = function () {
    scrollTopFn();
  };

  // change the flag and name of language when you change the language through laravel locale (Language Dropdown).
  // -------------------------------------------------------------------------------------------------------------
  let language = document.documentElement.getAttribute('lang');
  let langDropdown = document.getElementsByClassName('dropdown-language');
  if (language !== null && langDropdown.length) {
    // getting selected flag's name and icon class
    let selectedDropdownItem = document.querySelector('a[data-language=' + language + ']'),
        selectedFlag = selectedDropdownItem.childNodes[1].className,
        startsWith = 'fs-',
        classes = selectedFlag.split(' ').filter(function (v) {
          return v.lastIndexOf(startsWith, 0) !== 0;
        });

    selectedFlag = classes.join(' ').trim() + ' fs-3';

    // add 'selected' class to current language's dropdown options
    selectedDropdownItem.classList.add('selected');

   // set selected language's flag
    let setLangFlag = (document.querySelector(
      '.dropdown-language .dropdown-toggle'
    ).childNodes[1].className = selectedFlag);
  }

  // Notification
  // ------------
  const notificationMarkAsReadAll = document.querySelector('.dropdown-notifications-all');
  const notificationMarkAsReadList = document.querySelectorAll('.dropdown-notifications-read');

  // Notification: Mark as all as read
  if (notificationMarkAsReadAll) {
    notificationMarkAsReadAll.addEventListener('click', event => {
      notificationMarkAsReadList.forEach(item => {
        item.closest('.dropdown-notifications-item').classList.add('marked-as-read');
      });
    });
  }
  // Notification: Mark as read/unread onclick of dot
  if (notificationMarkAsReadList) {
    notificationMarkAsReadList.forEach(item => {
      item.addEventListener('click', event => {
        item.closest('.dropdown-notifications-item').classList.toggle('marked-as-read');
      });
    });
  }

  // Notification: Mark as read/unread onclick of dot
  const notificationArchiveMessageList = document.querySelectorAll('.dropdown-notifications-archive');
  notificationArchiveMessageList.forEach(item => {
    item.addEventListener('click', event => {
      item.closest('.dropdown-notifications-item').remove();
    });
  });

  // Init helpers & misc
  // --------------------

  // Init BS Tooltip
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // Accordion active class
  const accordionActiveFunction = function (e) {
    if (e.type == 'show.bs.collapse' || e.type == 'show.bs.collapse') {
      e.target.closest('.accordion-item').classList.add('active');
    } else {
      e.target.closest('.accordion-item').classList.remove('active');
    }
  };

  const accordionTriggerList = [].slice.call(document.querySelectorAll('.accordion'));
  const accordionList = accordionTriggerList.map(function (accordionTriggerEl) {
    accordionTriggerEl.addEventListener('show.bs.collapse', accordionActiveFunction);
    accordionTriggerEl.addEventListener('hide.bs.collapse', accordionActiveFunction);
  });

  // If layout is RTL add .dropdown-menu-end class to .dropdown-menu
  if (isRtl) {
    Helpers._addClass('dropdown-menu-end', document.querySelectorAll('#layout-navbar .dropdown-menu'));
  }

  // Auto update layout based on screen size
  window.Helpers.setAutoUpdate(true);

  // Toggle Password Visibility
  window.Helpers.initPasswordToggle();

  // Speech To Text
  window.Helpers.initSpeechToText();

  // Init PerfectScrollbar in Navbar Dropdown (i.e notification)
  window.Helpers.initNavbarDropdownScrollbar();

  // On window resize listener
  // -------------------------
  window.addEventListener(
    'resize',
    function (event) {
      // Hide open search input and set value blank
      if (window.innerWidth >= window.Helpers.LAYOUT_BREAKPOINT) {
        if (document.querySelector('.search-input-wrapper')) {
          document.querySelector('.search-input-wrapper').classList.add('d-none');
          document.querySelector('.search-input').value = '';
        }
      }
      // Horizontal Layout : Update menu based on window size
      let horizontalMenuTemplate = document.querySelector("[data-template^='horizontal-menu']");
      if (horizontalMenuTemplate) {
        setTimeout(function () {
          if (window.innerWidth < window.Helpers.LAYOUT_BREAKPOINT) {
            if (document.getElementById('layout-menu')) {
              if (document.getElementById('layout-menu').classList.contains('menu-horizontal')) {
                menu.switchMenu('vertical');
              }
            }
          } else {
            if (document.getElementById('layout-menu')) {
              if (document.getElementById('layout-menu').classList.contains('menu-vertical')) {
                menu.switchMenu('horizontal');
              }
            }
          }
        }, 100);
      }
    },
    true
  );

  // Manage menu expanded/collapsed with templateCustomizer & local storage
  //------------------------------------------------------------------

  // If current layout is horizontal OR current window screen is small (overlay menu) than return from here
  if (isHorizontalLayout || window.Helpers.isSmallScreen()) {
    return;
  }

  // If current layout is vertical and current window screen is > small

  // Auto update menu collapsed/expanded based on the themeConfig
  if (typeof TemplateCustomizer !== 'undefined') {
    if (window.templateCustomizer.settings.defaultMenuCollapsed) {
      window.Helpers.setCollapsed(true, false);
    }
  }

  // Manage menu expanded/collapsed state with local storage support If enableMenuLocalStorage = true in config.js
  if (typeof config !== 'undefined') {
    if (config.enableMenuLocalStorage) {
      try {
        if (
          localStorage.getItem('templateCustomizer-' + templateName + '--LayoutCollapsed') !== null &&
          localStorage.getItem('templateCustomizer-' + templateName + '--LayoutCollapsed') !== 'false'
        )
          window.Helpers.setCollapsed(
            localStorage.getItem('templateCustomizer-' + templateName + '--LayoutCollapsed') === 'true',
            false
          );
      } catch (e) {}
    }
  }
})();

// ! Removed following code if you do't wish to use jQuery. Remember that navbar search functionality will stop working on removal.
if (typeof $ !== 'undefined') {
  $(function () {
    // ! TODO: Required to load after DOM is ready, did this now with jQuery ready.
    window.Helpers.initSidebarToggle();
    // Toggle Universal Sidebar
  });

  (function ($) {
    "use strict";
    function centerModal() {
        $(this).css('display', 'block');
        var $dialog  = $(this).find(".modal-dialog"),
            offset       = ($(window).height() - $dialog.height()) / 2,
            bottomMargin = parseInt($dialog.css('marginBottom'), 10);

        // Make sure you don't hide the top part of the modal w/ a negative margin if it's longer than the screen height, and keep the margin equal to the bottom margin of the modal
        if(offset < bottomMargin) offset = bottomMargin;
        $dialog.css("margin-top", offset);
    }

    $(document).on('show.bs.modal', '.modal', centerModal);
    $(window).on("resize", function () {
        $('.modal:visible').each(centerModal);

    });
  })(jQuery);
}
