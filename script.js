'use strict';

var $checkbox = document.getElementsByClassName('show_completed');

if ($checkbox.length) {
  $checkbox[0].addEventListener('change', function (event) {
    var is_checked = +event.target.checked;

    var searchParams = new URLSearchParams(window.location.search);
    searchParams.set('show_completed', is_checked);

    window.location = '/index.php?' + searchParams.toString();
  });
}

flatpickr('#date', {
  enableTime: false,
  dateFormat: "Y-m-d",
  locale: "ru"
});
var $checkbox = document.getElementsByClassName('task__checkbox');

if ($checkbox.length) {
    for(var i = 0; i < $checkbox.length; i++) {
        $checkbox[i].addEventListener('change', function (event) {
          

            var searchParams = new URLSearchParams(window.location.search);
            searchParams.set('task_id', event.target.value);

            window.location = '/index.php?' + searchParams.toString();
          });        
    }  
}