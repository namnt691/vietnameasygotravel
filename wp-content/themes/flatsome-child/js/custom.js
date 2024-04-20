(function($) {



var currentDate = new Date();
    var nextDate = new Date();
    nextDate.setDate(nextDate.getDate() + 1);

    $("#datepicker1").datepicker({
      numberOfMonths: 2,
      defaultDate: currentDate,
      showCurrentAtPos: 0,
      minDate: 0,
    //  onSelect: function(dateText, inst) {
      //  var selectedDate = $(this).datepicker('getDate');
     //   selectedDate.setDate(selectedDate.getDate() + 1);
     //   $("#datepicker2").datepicker("setDate", selectedDate);
     // },
      dateFormat: 'dd-mm-yy',
      showButtonPanel: true
    });

  //  var urlParams = new URLSearchParams(window.location.search);
// var departureDate = urlParams.get('departure_date');
// if (departureDate) {
   
//    $('.departure_dateget > a').each(function() {
 //       var href = $(this).attr('href');
  //      if (href && href.indexOf('?') === -1) {
  //          href += '?departure_date=' + departureDate;
  //      } else if (href && href.indexOf('?') !== -1) {
  //          href += '&departure_date=' + departureDate;
   //     }
   //     $(this).attr('href', href);
  //  });
// }

//var urlParams = new URLSearchParams(window.location.search);
//    var departureDate = urlParams.get('departure_date');
//    if (departureDate) {
 //       var dateArr = departureDate.split('-');
  //      var formattedDate = dateArr[0] + '/' + dateArr[1] + '/' + dateArr[2];
   //     $('input[name="wt_date"]').val(formattedDate);
   //     $('input[name="wt_sldate"]').val(dateArr[2] + '_' + dateArr[1] + '_' + dateArr[0]);
  //  }

    
    $("#datepicker2").datepicker({
      numberOfMonths: 2,
      dateFormat: 'dd/mm/yy',
      minDate: 0,
    });

     // Set default values
    $("#datepicker1").datepicker("setDate", currentDate);
    $("#datepicker2").datepicker("setDate", nextDate);

    $(document).ready(function() {
 

  $('#datepicker1, #datepicker2').on('change', function() {
    updateTotalPrice();
  });

  function replaceDecimalSeparator(number) {
  return number.replace(/\./g, ',');
}

  function updateTotalPrice() {
    $('.room_rate').each(function() {
      var roomRate = $(this);
      var phong = roomRate.find('.phong');
      var dem = parseInt($('#datepicker2').datepicker('getDate') - $('#datepicker1').datepicker('getDate')) / (1000 * 60 * 60 * 24);
      if (isNaN(dem)) {
        dem = 0;
      }
      roomRate.find('.dem').text(dem);

      var priceFrom = parseInt(roomRate.find('.price_from').text().replace(/\D/g, ''));
      var totalPrice = phong.text() * dem * priceFrom;
      roomRate.find('.total-room-price .total-price').text('$' + replaceDecimalSeparator(totalPrice.toLocaleString()));


    });
  }

  updateTotalPrice();
});





})(jQuery);
