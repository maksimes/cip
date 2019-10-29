function Survey(params) {
    //сохраняем контекст в переменную и делаем ссылку добавления полей
    var they = this;
    var $linkAdd = $('<a style="margin-top: 20px;" href="#"><button>Добавить ' + params.nameDomEl + '</button></a>');
    var $newLinkLi = $('<li></li>').append($linkAdd).addClass('link_add_' + params.domElClass);

    //добавляем ссылку удаления новым элементам, которые можно удалять(оставляя минимально допустимое количество)
    this.addFormDeleteLink = function(newFormLi) {
        //создаем ссылку удаления
        var $removeFormLink = $('<li></li>').append('<a href="#"><button>Удалить '+ params.nameDomEl +'</button></a>').addClass('del_link_' + params.domElClass);
        newFormLi.append($removeFormLink);
        //удаляем поля при клике на неё
        $removeFormLink.on('click', function(e) {
            e.preventDefault();
            newFormLi.remove();
        });
    }
    //добавляем ссылки удаления полей
    params.collectionHolder.find('.' + params.domElClass).each(function () {
        they.addFormDeleteLink($(this));
    })
    //добавляем ссылки добавления полей
    params.collectionHolder.each(function () {
        $newLinkLi = $('<li></li>').append($linkAdd).addClass('link_add_' + params.domElClass);
        $(this).append($newLinkLi);
    })
    //сохраняем в индекс количество дочерних элементов
    params.collectionHolder.each(function () {
        $(this).data('index', $(this).find('.' + params.domElClass).length);
    })
    //при клике добавляем ещё один элемент формы
    $linkAdd.on('click', function (e) {
        e.preventDefault();
        they.addForm($(this).parents('.' + params.domElementsClass), $newLinkLi);
    })

    //получаем и инкрементируем значение дата прототипа коллекции
    this.addForm = function() {
        //проверяем не является ли экземпляром создания ответа
        if (params.collectionHolder.data(params.prototypeAttr)) {
            var prototype = params.collectionHolder.data(params.prototypeAttr);
        } else {
            var prototype = params.prototype_data;
        }
        var index = params.collectionHolder.data('index');
        var newForm = prototype;
        params.collectionHolder.data('index', index + 1);
        //добавляем значение индекса в имена дочерхних полей портотипа, для новых вопросов добавляем сразу 2 прототипа ответа
        var $newFormLi;
        if (params.domElClass === 'question') {
            var prototypeAnsw = params.collectionHolder.data('prototype-answer')
            var intanceAnsw1 = prototypeAnsw.replace(new RegExp(params.prototypeName,'g'), index).replace(new RegExp('__answer_prot__', 'g'), 0);
            var intanceAnsw2 = prototypeAnsw.replace(new RegExp(params.prototypeName,'g'), index).replace(new RegExp('__answer_prot__', 'g'), 1);
            var ulAnswersPlus2Answers = $('<ul class="answers"></ul>').append(intanceAnsw1).append(intanceAnsw2);
            ulAnswersPlus2Answers.children().each(function() {
                $(this).addClass('answer');
            });
            newForm = newForm.replace(new RegExp(params.prototypeName, 'g'), index);
            params.collectionHolder.data('index', index + 1);
            $newFormLi = $(newForm).addClass(params.domElClass).append(ulAnswersPlus2Answers);
            //новый объект создания ответов в вопросах по клику
            var newAnswersObj = new Survey({
                nameDomEl: 'ответ',
                collectionHolder: $newFormLi.find('ul.answers'),
                domElClass: 'answer',
                domElementsClass: 'answers',
                prototypeAttr: 'prototype-answer',
                prototype_data: prototypeAnsw,
                prototypeName: '__answer_prot__',
                questionIndex: index,
                questionPrototypeName: params.prototypeName,
                minDomEl: 2,
            })
        } else {
            if(params.questionPrototypeName) {
                newForm = newForm.replace(new RegExp(params.questionPrototypeName, 'g'), params.questionIndex);
            }
            newForm = newForm.replace(new RegExp(params.prototypeName, 'g'), index);
            params.collectionHolder.data('index', index + 1);
            $newFormLi = $(newForm).addClass(params.domElClass);
        }
        //добавляем в форму новые поля и ссылку на их удаление
        $newLinkLi.before($newFormLi);
        this.addFormDeleteLink($newFormLi);
    }
}

//создаем экзепляры вопросов и ответов
var questionObj = new Survey({
    nameDomEl: 'вопрос',
    collectionHolder: $('.questions'),
    domElClass: 'question',
    domElementsClass: 'questions',
    prototypeAttr: 'prototype-question',
    prototypeName: '__question_prot__',
    minDomEl: 1,
})

$('.answers').each(function () {
    var answersObj = new Survey({
        nameDomEl: 'ответ',
        collectionHolder: $(this),
        domElClass: 'answer',
        domElementsClass: 'answers',
        prototypeAttr: 'prototype',
        prototypeName: '__answer_prot__',
        minDomEl: 2,
    })
})

//отправка и валидация результатов опроса
$('#send-survey').on('click', function(e){
    e.preventDefault();
    var error;
    $('.question-poll').each(function () {
        if($(this).data('required') == '1') {
            $(this).each(function () {
                if($(this).find('input:checked').length < 1) {
                    error = 'Ответьте на все вопросы со звездочкой';
                };
            })
        }
    })
    if(error != undefined) {
        $('#errors').text(error);
        return;
    }
    var answers_id = {};
    $('#survey-poll-form input:checked').each(function (i) {
        answers_id[i] = $(this).attr('value');
    })
    var json_answ_id = JSON.stringify(answers_id);
    $.ajax({
        url: '/poll',
        type: "post",
        data: json_answ_id,
        success: function(data) {
            location.href = 'view_result/' + data;
        }
    });
});

//визулизация результатов опроса
var computedWidthRes;
var countAnswRes;
var countUsersResPull = $('#result-poll').data('users-count');
$('#result-poll .js-result-pull').each(function () {
    countAnswRes = $(this).data('count');
    computedWidthRes = 100 * countAnswRes / countUsersResPull;
    $(this).children('.result-pull-in').css('width', computedWidthRes +'px');
});


$('#send-filter').on('click', function(e){
    e.preventDefault();

    var questions = {};
    var question = [];

    $('#filter-form .question-poll').each(function () {
        $(this).find('input:checked').each(function () {
            question.push($(this).attr('value'));
        })
        if ($(this).find('input:checked').length > 0) {
            questions[$(this).data('question_id')] = question;
        }
        question = [];
    })
    console.log(questions);
    var json_answ_id = JSON.stringify(questions);
    var url = $('#filter-form').attr('action');
    $.ajax({
        url: url,
        type: "post",
        data: json_answ_id,
        dataType:'json',
        success: function(data) {
            var useranswers_count = Object.keys(data['useranswers_count']).map(function(key) {
                return [Number(key), data['useranswers_count'][key]];
            });

            var jstr = JSON.stringify(data);
            // var str = '';
            // var keys = Object.keys(data['useranswers_count'])
            // for (var i = 0, l = keys.length; i < l; i++) {
            //     str += '['+ keys[i]+ ']'
            // }
            //
            //
            //
            // for (i = 0; i < useranswers_count.length; i++) {
            //     useranswers_count
            // }


            var strJSON = JSON.stringify(data['useranswers_count']);
            console.log(typeof strJSON);

            var new_form = $('<form style="display: hidden" method="post" action="/view_result/'+ data['survey_id'] +'"></form>');
            var inp1 = $('<input type="hidden" name="users_count" value="'+ data['users_count'] +'"/>');
            var inp2 = $('<input type="hidden" id="useranswers-count" name="useranswers_count" value="'+ strJSON +'"/>');
            $('body').append(new_form);
            // $('#useranswers-count').attr('value', useranswers_count);
            // console.log($('#useranswers_count'));
            new_form.append(inp1).append(inp2).submit();
        }
    });
});