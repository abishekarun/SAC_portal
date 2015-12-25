(function(){
var app=angular.module('app', ['ngRoute','ngCookies']);

app.controller("commentCtrl", ['$scope', '$http','$rootScope','$cookies', function commentCtrl($scope, $http,$rootScope,$localstorage) {
        $scope.url = 'submit.php';
        $scope.formsubmit = function(isValid) {
        $username=$cookies.username;
        $fullname=$localstorage.fullname;
            if (isValid) {

                $http.post($scope.url, {"username":$username,"meeting_name": $scope.meeting_name, "message": $scope.message,"meeting_year":$scope.meeting_year}).
                        success(function(data, status) {
                            console.log(data);
                            $scope.status = status;
                            $scope.data = data;
                            $scope.result = data;
                            $scope.fullname=$fullname.toUpperCase();
                            $scope.message=''; 
                         
                        })
            }else{
                
                  alert('Form is not valid');
            }

        }


    }]);

app.
config(function ($routeProvider,$locationProvider){
	$routeProvider
	.when('/index',
	{
		controller:'nav',
		templateUrl:'pages/main.html'
		})
	.when('/',
	{
		controller:'loginController',
		templateUrl:'pages/login.html'
		})
	.when('/upload',
	{
		controller:'UploadController',
		templateUrl:'pages/file_upload.html'
		})
	.when('/login',
	{
		controller:'loginController',
		templateUrl:'pages/login.html'
		})
	.otherwise({ redirectTo:'/'});
	//$provide.factory('a',function(){return 'true';})
	//$locationProvider.hashPrefix('!');

}).
run(function($rootScope, $location,$cookies) {
    $rootScope.$on( "$routeChangeStart", function(event, next, current) {
      if($cookies.username)
		$location.path('/'+'index');
    });
    /*$rootScope.$on('$stateChangeStart', 
       function(event, toState, toParams, fromState, fromParams){ 
          event.preventDefault();
          window.history.forward();
    });*/
  });
app.controller('nav',['$scope','$http','$location','$rootScope','$cookies', function($scope,$http,$location,$rootScope,$cookies) 
{

	if(!($cookies.username))
		$location.path('/'+'login');
	$scope.username =$cookies.username ;
	$rootScope.loggedinUser=$scope.username;
	$scope.admin=false;
	$scope.video=false;
	$scope.document=false;
  	/* Setting a cookie
  	$cookies.myFavorite = 'oatmeal';
	delete $cookies.myFavorite;
  	var favoriteCookie = $cookies.myFavorite;
	console.log(favoriteCookie);
	*/
	$scope.getitems=function(){
	$http({
		method: 'POST', 
		url: 'api/getitems.php',
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
    }).
  	success(function(response) {
	$scope.years=response;
	//console.log(response[1]);
	//console.log($scope.years);
		}).
	error(function(response) {
	});
	}
	$scope.show=function(){
		if($scope.username === "me12b113"){
		$scope.admin=true;
		}
		return $scope.admin;
	}
	$scope.home=function(){
		$scope.video=false;
		$scope.document=false;	
	}
	$scope.update=function(){
		var toBeSendData = $.param({q : $scope.q });
		$http({
			method: 'POST', 
			url: 'api/search.php',
			data:  toBeSendData,
			headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	    }).
	  	success(function(response) {
		$scope.searchresults=response.data;
			}).
		error(function(response) {
		});
	}
	$scope.add=function(){
		$scope.video=false;
		$scope.document=false;
		//$location.path('/'+'upload');
		console.log("success");
	}
	$scope.tiles=function(a,b){
		$scope.files=a.files;
		$scope.meeting_year=b['year'];
		$scope.meeting_name=a['meeting_name'];
		$scope.tiles_hide=true;
		//var commentdata = $.param({meeting_name : $scope.meeting_name });
		$http.post('getcomments.php', {"meeting_name": $scope.meeting_name,"meeting_year":$scope.meeting_year}).
	                        success(function(data, status) {
	                        	//console.log(data);	                            //console.log(data[1][0]['message']);
	                            $scope.comments = data;
	                                       
	                        })
			
	      

	}

	$scope.tileshow=function(){
		$scope.tiles_hide=false;
		$scope.video=false;
		$scope.document=false;
	}
	$scope.load=function(a,b){
		//console.log("success");
		$scope.tiles_hide=false;
		if(b=="pdf" || b=="PDF"){
			$scope.document=true;
			$scope.video=false;
			$scope.type="application/"+b;
		}
		else{
			$scope.video=true;
			$scope.document=false;
			$scope.type="video/"+b;
		}
		$scope.path="../SAC_Portal/"+decodeURIComponent(a);
		console.log($scope.path);
	}
	$scope.delete=function(a,b,c,d){
		//console.log("success");
		//console.log(b);
		//console.log(c);
		var toBeSendData = $.param({file_name : a , year : b ,type : c ,meeting_name : d });
		$http({
			method: 'POST', 
			url: 'api/delete.php',
			data:  toBeSendData,
			headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	    }).
	  	success(function(response) {
	  		//console.log(response);
		if(response.status == 1)
			//console.log("success");
			$scope.video=false;
			$scope.document=false;
			$scope.getitems();
			}).
		error(function(response) {
		});
	}
	$scope.logout=function(){
		$scope.username="";
		delete $cookies.username;
		delete $cookies.fullname;
		$location.path('/'+'login');
	}	
}]);

app.controller('loginController',['$scope','$http','$location','$rootScope','$cookies', function($scope,$http,$location,$rootScope,$cookies) 
{

	$scope.submitForm = function() 
	{

		if (! $scope.userForm.$valid) 
		{
			alert('Invalid Login');
		}
	};
	$scope.login = function (user)
	{
		var toBeSendData = $.param(user);
		$http({
			method: 'POST', 
			url: 'https://students.iitm.ac.in/mobapi/ldap/login.php',
			data:  toBeSendData,
			headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	    }).
	  	success(function(response) {
		//console.log(response[0].username);
		$cookies.username=response[0].username;
		$cookies.fullname=response[0].fullname;
		$scope.username=$cookies.username;
		$rootScope.loggedinUser=$scope.username;
		$location.path('/'+'index');
			}).
		error(function(response) {
			$scope.invalid=true;
			$scope.message=response.message;
		});
	};

}]);
app.controller('UploadController',['$scope','$http','$cookies','$location','fileUpload', function($scope,$http,$cookies,$location,$fileUpload) 
{

	$scope.submit=function(user){
	console.log("success");
		var file = $scope.myFile;
		file=file.replace(" ","");
		var uploadUrl = 'api/upload.php';
		fileUpload.uploadFileToUrl(file, uploadUrl);
	 
	}
}]);
})();
