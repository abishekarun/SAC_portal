(function(){
var app=angular.module('app', ['ngRoute','ionic.utils']);

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
	.otherwise({ redirectTo:'/login'});
	//$provide.factory('a',function(){return 'true';})
	//$locationProvider.hashPrefix('!');

}).
run(function($rootScope, $location,$localstorage) {
    $rootScope.$on( "$routeChangeStart", function(event, next, current) {
      if($localstorage.get('username'))
		$location.path('/'+'index');
    });
    /*$rootScope.$on('$stateChangeStart', 
       function(event, toState, toParams, fromState, fromParams){ 
          event.preventDefault();
          window.history.forward();
    });*/
  });
app.controller('nav',['$scope','$http','$localstorage','$location','$rootScope', function($scope,$http,$localstorage,$location,$rootScope) 
{

	if(!($localstorage.get('username')))
		$location.path('/'+'login');
	$scope.username =$localstorage.get('username') ;
	$rootScope.loggedinUser=$scope.username;
	$scope.admin=false;
	$scope.video=false;
	$scope.document=false;
	$scope.getitems=function(){
	$http({
		method: 'POST', 
		url: 'api/getitems.php',
		headers: {'Content-Type': 'application/x-www-form-urlencoded'}
    }).
  	success(function(response) {
	$scope.years=response;
	//console.log(response.year);
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
	$scope.tiles=function(a){
		$scope.files=a;
		$scope.tiles_hide=true;
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
		$localstorage.clear('username');
		$location.path('/'+'login');
	}	
}]);

app.controller('loginController',['$scope','$http','$localstorage','$location','$rootScope', function($scope,$http,$localstorage,$location,$rootScope) 
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
		//console.log(response);
		$localstorage.set('username',user.roll);
		$scope.username=$localstorage.get('username');
		$rootScope.loggedinUser=$scope.username;
		$location.path('/'+'index');
			}).
		error(function(response) {
			$scope.invalid=true;
			$scope.message=response.message;
		});
	};

}]);
/*app.directive('fileModel', ['$parse', function ($parse) {
    return {
        restrict: 'A',
        link: function(scope, element, attrs) {
            var model = $parse(attrs.fileModel);
            var modelSetter = model.assign;
            
            element.bind('change', function(){
                scope.$apply(function(){
                    modelSetter(scope, element[0].files[0]);
                });
            });
        }
    };
}]);
app.service('fileUpload', ['$http', function ($http) {
    this.uploadFileToUrl = function(file, uploadUrl){
        var fd = new FormData();
        fd.append('file', file);
        $http.post(uploadUrl, fd, {
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined}
        })
        .success(function(){
        })
        .error(function(){
        });
    }
}]);
*/
app.controller('UploadController',['$scope','$http','$localstorage','$location','fileUpload', function($scope,$http,$localstorage,$location,$fileUpload) 
{

	$scope.submit=function(user){
	console.log("success");
		var file = $scope.myFile;
		file=file.replace(" ","");
		var uploadUrl = 'api/upload.php';
		fileUpload.uploadFileToUrl(file, uploadUrl);
	 
	}
}]);
/*app.directive('dirDisqus', ['$window', function($window) {
        return {
            restrict: 'E',
            scope: {
                disqus_shortname: '@disqusShortname',
                disqus_identifier: '@disqusIdentifier',
                disqus_title: '@disqusTitle',
                disqus_url: '@disqusUrl',
                disqus_category_id: '@disqusCategoryId',
                disqus_disable_mobile: '@disqusDisableMobile',
                disqus_config_language : '@disqusConfigLanguage',
                disqus_remote_auth_s3 : '@disqusRemoteAuthS3',
                disqus_api_key : '@disqusApiKey',
                disqus_on_ready: "&disqusOnReady",
                readyToBind: "@"
            },
            template: '<div id="disqus_thread"></div><a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>',
            link: function(scope) {

                // ensure that the disqus_identifier and disqus_url are both set, otherwise we will run in to identifier conflicts when using URLs with "#" in them
                // see http://help.disqus.com/customer/portal/articles/662547-why-are-the-same-comments-showing-up-on-multiple-pages-
                if (typeof scope.disqus_identifier === 'undefined' || typeof scope.disqus_url === 'undefined') {
                    throw "Please ensure that the `disqus-identifier` and `disqus-url` attributes are both set.";
                }

                scope.$watch("readyToBind", function(isReady) {

                    // If the directive has been called without the 'ready-to-bind' attribute, we
                    // set the default to "true" so that Disqus will be loaded straight away.
                    if ( !angular.isDefined( isReady ) ) {
                        isReady = "true";
                    }
                    if (scope.$eval(isReady)) {
                        console.log('remote'+scope.disqus_remote_auth_s3);
                        // put the config variables into separate global vars so that the Disqus script can see them
                        $window.disqus_shortname = scope.disqus_shortname;
                        $window.disqus_identifier = scope.disqus_identifier;
                        $window.disqus_title = scope.disqus_title;
                        $window.disqus_url = scope.disqus_url;
                        $window.disqus_category_id = scope.disqus_category_id;
                        $window.disqus_disable_mobile = scope.disqus_disable_mobile;
                        $window.disqus_config =  function () {
                            this.language = scope.disqus_config_language;
                            this.page.remote_auth_s3 = scope.disqus_remote_auth_s3;
                            this.page.api_key = scope.disqus_api_key;
                            if (scope.disqus_on_ready) {
                                this.callbacks.onReady = [function () {
                                    scope.disqus_on_ready();
                                }];
                            }
                        };
                        // get the remote Disqus script and insert it into the DOM, but only if it not already loaded (as that will cause warnings)
                        if (!$window.DISQUS) {
                            var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
                            dsq.src = '//' + scope.disqus_shortname + '.disqus.com/embed.js';
                            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
                        } else {
                            $window.DISQUS.reset({
                                reload: true,
                                config: function () {
                                    this.page.identifier = scope.disqus_identifier;
                                    this.page.url = scope.disqus_url;
                                    this.page.title = scope.disqus_title;
                                    this.language = scope.disqus_config_language;
                                    this.page.remote_auth_s3=scope.disqus_remote_auth_s3;
                                    this.page.api_key=scope.disqus_api_key;
                                }
                            });
                        }
                    }
                });
            }
        };
    }]);*/
})();
angular.module('ionic.utils', [])

.factory('$localstorage', ['$window', function($window) {
  return {
    set: function(key, value) {
      $window.localStorage[key] = value;
    },
    get: function(key) {
      return $window.localStorage[key];
    },
    clear:function(key){
    	delete $window.localStorage[key];
    },
  }
}]);