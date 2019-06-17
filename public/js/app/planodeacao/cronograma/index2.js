function ProjetoCtrl($scope, $http) {
    //$scope.projeto = {};
    $scope.path = base_url + '/planodeacao/cronograma/retorna-planodeacao/idplanodeacao/1/format/json';
    $scope.projeto = {
        idplanodeacao: 1,
        nomprojeto: 'Nome do projeto teste',
        nomcodigo: '4154',
        patrocinador: 'RICARDO CORTEZ TOLEDO',
        gerente: 'ALUNO - TREINO GERAL',
        adjunto: 'ALUNO - TREINO GERAL',
        meta: '(486.d) 14/04/2009 à 13/08/2010',
        tendencia: '13/08/2010 (486.d)',
        objetivo: '02 - Valorizar o Servidor',
        acao: '02 - Racionalizar a Gestão Logística',
        previsto: 91,
        concluido: 91,
        ultimaFoto: '29/03/2010',
        status: 2,
        natureza: 'INFRAESTRUTURA OBRAS',
        flacopa: 'N',
        grupos: [
            {
                idatividadecronograma: 1, nomatividadecronograma: 'grupo 1',
                entregas: [
                    {
                        idatividadecronograma: 4, nomatividadecronograma: 'entrega 1',
                        atividades: [
                            {idatividadecronograma: 6, nomatividadecronograma: 'atividade 1'}
                        ]
                    },
                    {idatividadecronograma: 5, nomatividadecronograma: 'entrega 2'}
                ]
            }/*,
             {idatividadecronograma:2,nomatividadecronograma:'grupo 2'},
             {idatividadecronograma:3,nomatividadecronograma:'grupo 3'}
             */
        ]

    };
    $scope.flacopa = ($scope.projeto.flacopa === 'S') ? 2 : 3;
    $scope.showCopa = ($scope.projeto.flacopa === 'S') ? true : false;

    function refreshItems() {
        /*
         $http.get($scope.path).success(function(data){

         });
         */

        //console.log(data.projeto);

        //console.log($scope.showCopa);


    }
    ;


    refreshItems();
}
;
/*
 * angular.module('projeto', []).
 factory('projeto',function($http, $q){
 return{
 apiPath: base_url + '/planodeacao/cronograma/retorna-planodeacao/idplanodeacao/1',
 getAllItems: function(){
 //Creating a deferred object
 var deferred = $q.defer();

 //Calling Web API to fetch shopping cart items
 $http.get(this.apiPath).success(function(data){
 //Passing data to deferred's resolve function on successful completion
 deferred.resolve(data);
 }).error(function(){

 //Sending a friendly error message in case of failure
 deferred.reject("An error occured while fetching items");
 });

 //Returning the promise object
 return deferred.promise;
 }
 }
 }

 function ProjetoCtrl($scope, projeto )
 {
 $scope.items = [];

 function refreshItems(){
 projeto.getAllItems().then(function(data){
 console.log(data);
 $scope.items = data;
 },
 function(errorMessage){
 $scope.error = errorMessage;
 });
 };

 refreshItems();
 };

 */