var app = angular.module('comment-system', []);


app.controller("commentCtrl", ['$scope', '$http', function commentCtrl($scope, $http) {
        $scope.url = 'submit.php';
        $scope.file_id=12;

        $scope.formsubmit = function(isValid) {


            if (isValid) {
              
                $http.post($scope.url, {"user_id": $scope.user_id, "file_id": $scope.file_id, "message": $scope.message}).
                        success(function(data, status) {
                            console.log(data);
                            $scope.status = status;
                            $scope.data = data;
                            $scope.result = data; 
                         
                        })
            }else{
                
                  alert('Form is not valid');
            }

        }

    }]);

app.controller("getcommentCtrl",['$scope','$http',function getcommentCtrl($scope,$http)
{
$scope.url='getcomments.php';
$scope.file_id=12;
$scope.comments=0

var res=$http.get($scope.url, {"file_id": $scope.file_id}).
                        success(function(data, status) {
                            console.log(data);
                            $scope.status = status;
                            $scope.comments = data;                         
                        });



     
}]);
