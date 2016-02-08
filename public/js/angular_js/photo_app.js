
$(document).ready(function() {
    var options = {minMargin: 5, maxMargin: 15, itemSelector: ".item", firstItemClass: "first-item"};

  $(".fancybox").fancybox({
    prevEffect  : 'none',
    nextEffect  : 'none',
    helpers   : {
      title : { type : 'inside' },
      buttons : {}

    }
  });

    $(".container").rowGrid(options);
  //nendless scrolling
 /* $(window).scroll(function() {
      if($(window).scrollTop() + $(window).height() == $(document).height()) {
        $(".container").append("<div class='item'><img src='" + photos + "' width='140' height='100' /></div>");
          $(".container").rowGrid("appended");
      }
  });*/

});

//Start angular
var app = angular.module('app', ['angularUtils.directives.dirPagination']);

(function(){

  var homeController = function ($scope, $http){
    $http.get('/?module=Dietary&page=photos&action=view_photos_json').then(function(response){
      angular.forEach(response.data, function(e){
        if(e.tags){
          e.tags = e.tags.split(",");
        }
      });
      $scope.photos = response.data;
    });

  }

  homeController.$inject = ['$scope', '$http'];
  app.controller('homeController', homeController);
}());