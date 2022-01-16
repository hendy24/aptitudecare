{literal}    

$('#food-allergies').selectize({
     delimiter: ',',
     load: function(query, callback) {
          var patientId = $('.patient-id').val();
          $.ajax({
               url: SITE_URL,
               page: 'patientInfo',
               action: 'fetchAllergies',
               patientId: patientId,
               type: 'GET',
               error: function() {
                    console.log('failed');
               }, 
               success: function(response) {
                    console.log(response);
                    callback({value: response.id, text: response.name});
               }
          });
     },               
     create: function (input, callback) {
          console.log($(this).parent().next('input:hidden').attr('name'));
          $.post(SITE_URL, {
               page: 'patient_info',
               action: 'addAllergy',
               name: input,
          },
          function(response) {
               console.log(response);
               callback({value: response.id, text: response.name});
          }, 'json');


     }

});

$('#food-dislikes').selectize({
     delimiter: ',',
     load: function(query, callback) {
          var patientId = $('.patient-id').val();
          $.get(SITE_URL, {
               page: 'patientInfo',
               action: 'fetchDislikes',
               patientId: patientId
          },
          function(response, callback) {
               console.log(response);
               callback({value: response.id, text: response.name});
          }
          );
     },
     create: function (input, callback) {
          console.log($(this).parent().next('input:hidden').attr('name'));
          $.post(SITE_URL, {
               page: 'patient_info',
               action: 'addDislike',
               name: input,
          },
          function(response) {
               console.log(response);
               callback({value: response.id, text: response.name});
          }, 'json');


     }

});

$('.special-request').selectize({
     create: function(input, callback) {
          $.post(SITE_URL, {
               page: 'patientInfo',
               action: 'addSpecialRequest',
               name: input,
          },
          function(response) {
               callback({value: response.id, text: response.name});
          }, 'json');
     }
});

$('.beverages').selectize();


// add minus icon for collapse element which is open by default
$(".collapse.show").each(function() {
     $(this).prev(".card-header").find(".fa").addClass("fa-minus").removeClass("fa-plus");
});

// toggle plus minus icon on show hide of collapse element
$(".collapse").on('show.bs.collapse', function() {
     $(this).prev(".card-header").find(".fa").removeClass("fa-plus").addClass("fa-minus");
}).on('hide.bs.collapse', function() {
     $(this).prev(".card-header").find(".fa").removeClass("fa-minus").addClass("fa-plus");
});

{/literal}