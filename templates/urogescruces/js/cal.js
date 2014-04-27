function showmessage(){

}
$(function () {

    setTimeout(showmessage,2000);
    $( "#inline-datepicker" ).datepicker();


});

/* Curve chart starts */

$(function () {
    var sin = [], cos = [];
    for (var i = 0; i < 14; i += 0.5) {
        sin.push([i, Math.sin(i)]);
        cos.push([i, Math.cos(i)]);
    }


    
    $('#reportrange').daterangepicker({
        startDate: moment().subtract('days', 7),
        endDate: moment(),
        minDate: '01/01/2012',
        maxDate: '12/31/2014',
        dateLimit: { days: 60 },
        showDropdowns: true,
        showWeekNumbers: true,
        timePicker: false,
        timePickerIncrement: 1,
        timePicker12Hour: true,

        opens: 'left',
        buttonClasses: ['btn btn-default'],
        applyClass: 'btn-small btn-primary',
        cancelClass: 'btn-small',
        format: 'DD/MM/YYYY',
        separator: ' to ',
        locale: {
            applyLabel: 'Fijar',
            fromLabel: 'Desde',
            toLabel: 'Hasta',
            customRangeLabel: 'Custom Range',
            daysOfWeek: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi','Sa'],
            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Augosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            firstDay: 1
        }
     },
     function(start, end) {
      console.log("Callback has been called!");
      $('#reportrange span').html(start.format('D MMMM, YYYY') + ' - ' + end.format('D MMMM, YYYY'));
     });
     $('#reportrange span').html(moment().subtract('days', 7).format('D MMMM, YYYY') + ' - ' + moment().format('D MMMM, YYYY'));

    
});