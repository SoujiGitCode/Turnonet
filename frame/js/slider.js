var owl_day;
var owl_opt;
var item_day = 0;
var items_opt=0;
var item_opt=0;
var carrousel_item = 0;

$(document).ready(function() {
   owl_day = $('#owl-dates').owlCarousel({
      items: 7,
      startPosition: $(".cell-day").length - 1,
      nav: false,
      dots: false,
      autoPlay: false,
      touchDrag: false,
      mouseDrag: false,
      afterInit: initDay()
   });

   owl_opt = $('#owl-opt').owlCarousel({
      items: 7,
      nav: false,
      dots: false,
      autoPlay: false,
      touchDrag: false,
      mouseDrag: false,
   });


  $( ".cell-opt" ).click(function() {
 console.log("jola");
});
});

function selectDay(item) {

   item_day = item;
   setItemDay();
}

function initDay() {
   item_day = $(".cell-day").length - 1;
   setItemDay();
}

function NextDay() {

   moreDay();
   owl_day.trigger('next.owl.carousel');
}

function PrevDay() {
   minusDay();

   owl_day.trigger('prev.owl.carousel');
}

function NextOpt() {
moreOpt();
   owl_opt.trigger('next.owl.carousel');
}

function PrevOpt() {
minusOpt();
   owl_opt.trigger('prev.owl.carousel');
}

function minusDay() {
   item_day = item_day - 1;
   if (item_day < 0) {
      item_day = 0;
   }
   setItemDay();
}

function moreDay() {
   item_day = item_day + 1;
   if (item_day >= $(".cell-day").length - 1) {
      item_day = $(".cell-day").length - 1;
   }
   setItemDay();
}

function setItemDay() {

   $(".cell-day").removeClass('day-active');
   $("#cell-day-" + item_day).addClass('day-active');
   let date = $("#cell-day-" + item_day).data("filter");
   let id = $("#cell-day-" + item_day).data("id");
   $.get("/hives/get_inspections/" + date + "/" + id, function(data) {
      if (items_opt != 0) {
         for (i = 0; i < items_opt; i++) {
            owl_opt.trigger('remove.owl.carousel', i);
         }
      }
      items_opt = data.lists.length;
      item_opt=0;
      for (var i = 0; i < data.lists.length; i++) {
         if (i == 0) {
            owl_opt.owlCarousel('add', '<div class="cell-opt opt-active" id="cell-opt-'+i+'" onclick="selectOpt(this.id)"><div class="circle-opt"></div></div>').owlCarousel('update');
         } else {
            owl_opt.owlCarousel('add', '<div class="cell-opt" id="cell-opt-'+i+'" onclick="selectOpt(this.id)"><div class="circle-opt"></div></div>').owlCarousel('update');
         }
      }
   });
}

function minusOpt() {
   item_opt = item_opt - 1;
   if (item_opt < 0) {
      item_opt = 0;
   }
   selectOpt(item_opt);
}

function moreOpt() {
   item_opt = item_opt + 1;
   if (item_opt >= $(".cell-opt").length - 1) {
      item_opt = $(".cell-opt").length - 1;
   }
   selectOpt(item_opt);
}

function selectOpt(item) {

$(".cell-opt").removeClass('opt-active');
   $("#cell-opt-" + item).addClass('opt-active');
}
