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