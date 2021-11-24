const software = [  
{ name:"Название №15", platform: "Android", category: "Прикладное", rating: "3.4", price: "540", img: "category-1.png" },
{ name:"Название №239", platform: "Windows", category: "Системное", rating: "8.5", price: "1200", img: "category-2.png" },
{ name:"Название №41", platform: "IOS", category: "Прикладное", rating: "11.3", price: "5100", img: "category-1.png" },
{ name:"Название №57", platform: "IOS", category: "Прикладное", rating: "8.9", price: "590", img: "category-1.png" },
{ name:"Название №2", platform: "Windows", category: "Системное", rating: "4.7", price: "3450", img: "category-2.png" },
{ name:"Название №21", platform: "Linux", category: "Инструментальное", rating: "8.7", price: "2150", img: "slide-3.png" },
{ name:"Название №296", platform: "Android", category: "Инструментальное", rating: "11.2", price: "8450", img: "slide-3.png" },
{ name:"Название №79", platform: "Windows", category: "Прикладное", rating: "6.9", price: "150", img: "category-1.png" },
{ name:"Название №12", platform: "Windows", category: "Прикладное", rating: "8.1", price: "233", img: "category-1.png" },
{ name:"Название №389", platform: "IOS", category: "Прикладное", rating: "2.3", price: "90", img: "category-1.png" },
{ name:"Название №36", platform: "Linux", category: "Системное", rating: "4.8", price: "665", img: "category-2.png" },
{ name:"Название №38", platform: "Windows", category: "Инструментальное", rating: "8.2", price: "600", img: "slide-3.png" },
{ name:"Название №58", platform: "Android", category: "Системное", rating: "12.7", price: "350", img: "category-2.png" },
{ name:"Название №4", platform: "Linux", category: "Прикладное", rating: "9.7", price: "2690", img: "category-1.png" },
{ name:"Название №7", platform: "Windows", category: "Инструментальное", rating: "6.6", price: "590", img: "slide-3.png" }
];
const properties = {
	'platform':    { type: 'not_num',  index: 0 ,  val: ['Android', 'IOS', 'Linux', 'Windows']},
	'category':     { type: 'not_num',    index: 1,     val: ['Прикладное', 'Системное', 'Инструментальное']},
	'rating':   { type: 'num',    index: 2,     val: ['1_3', '3_6', '6_8', 'm8']},
    'price':     { type: 'num',    index: 3,     val: ['0_200', '200_500', '500_1000', '1000_2000', 'm2000']}
};

var arr_all = [];

$(document).ready(function()
{
    $.each(properties, function(){arr_all.push(true);});

	printsoftware(software, '.items-list');
	$('.categories input').change(function()
	{
        // Корректировка чекбоксов
        correctAllCheckBoxes(properties);
        // Текущий фильтр
        var curFilter = readCurFilters(properties);
        // Блокировка чекбоксов, при которых не будет результатов
        blockBadCheckBoxes(curFilter);
        // Получение по фильтрам ПО
		var filteredsoftware = applyFilters(software, curFilter, properties);
        // ПО
		printsoftware( filteredsoftware, '.items-list' );
	});
});

function blockBadCheckBoxes(filter) {

    $.each(properties, function (index, value) {
        value.val.forEach(element => {
            var item = $("." + index + "-ul input[value='" + element + "']");
            if (getNumOfsoftware(Object.assign({}, filter), element, index) == 0)
                item.prop('disabled', true);
            else {
                item.prop('disabled', false);
            }
        });
    });
}

function getNumOfsoftware(filter, pushItem, index) {
    filter[index] = [pushItem];
    return applyFilters(software, filter, properties).length;
}

function applyFilters(software, filter, properties) {
    var result = [];
    var ok = [];
    software.forEach(soft => {
        $.each(properties, function(index, value) {
            ok[value.index] = false;
            if (filter[index].indexOf('all') > -1) 
            {
                ok[value.index] = true;
            }
            else switch (value.type) {
                case 'not_num':
                    ok[value.index] = filtersoftTypeNotNum(soft, filter, index, value);
                    break;
                case 'num':
                    ok[value.index] = filtersoftTypeNum(soft, filter, index, value);
                    break;
                default:
                    console.log('filter type ' + value.type + ' is not exists');
                    alert('ERROR', 'filter type ' + value.type + ' is not exists');
            }
        });
        var thatsoftIsOk = true;
        ok.forEach(element => {
            if (element == false) thatsoftIsOk = false;
        });
        if (thatsoftIsOk) result.push(soft);
	});
	return result;
}

function filtersoftTypeNotNum(soft, filter, Index, Value) {
    if (filter[Index].indexOf(soft[Index]) > -1) 
        return true;
    return false;
}

function filtersoftTypeNum(soft, filter, Index, Value) {
    var ok = false;

    Value.val.forEach(element => {
        if (filter[Index].indexOf(element) > -1)
        {
            if (element.includes('_'))
            {
                var arr = element.split('_');
                var left = parseFloat(arr[0]);
                var right = parseFloat(arr[1]);
                if (soft[Index] >= left && soft[Index] <= right) 
                    ok = true;
            }
            if (element.includes('l'))
            {
                if (soft[Index] < parseFloat(element.replace('l', ''))) 
                    ok = true;
            }
            if (element.includes('m'))
            {
                if (soft[Index] > parseFloat(element.replace('m', ''))) 
                    ok = true;
            }
        }
    });
    return ok;
}

function correctAllCheckBoxes(properties) {
    $.each(properties, function(index, value) {
        var className = '.' + index + '-ul';
        var ind = value.index;
        correctCheckBoxes(className, ind);
    });
}

function correctCheckBoxes(className, index) {

    var all = $(className + ' .all');
    var items = $(className + ' input:not(.all)');
    var count = $(className + ' input:not(.all):checked').length;

    if (all.is(':checked') && arr_all[index] == false) // нажали на all
    {
        items.prop('checked', true);
    }
    else
    {
        var enableItemsCount = 0;
        var selectedEnableCount = 0;
        $.each(items, function(index, value){
            if ($(value).is(':disabled') == false) {
                enableItemsCount++;
            }
        });
        $.each($(className + ' input:not(.all):checked'), function(index, value){
            if ($(value).is(':disabled') == false) {
                selectedEnableCount++;
            }
        });
        // Если выбраны все элементы или ни одного - загорается чекбокс all. Иначе он неактивен
        all.prop('checked', (count == 0 || enableItemsCount == selectedEnableCount));
    }
    arr_all[index] = all.is(':checked');
}

function readCurFilters(properties) {
    var result = [];

    $.each(properties, function(index){
        var searchIDs = $(".categories input[name='" + index + "']:checkbox:checked").map(function(){
			return $(this).val();
		}).get(); 
		result[index] = searchIDs;
    });
	return result;
}

function printsoftware(softwareArray, selector) {
    const template = 
'<li class="item"> <p class="software-name">{{name}}</p> <p class="software-platform">{{platform}}</p> <p class="software-category">{{category}}</p> <p class="software-rating">{{rating}}</p> <p class="software-price">{{price}}$</p> </li>';
    var output = "";

    softwareArray.forEach(element => {
        var tmpItem;
        tmpItem = template.replace('{{name}}', element.name);
        tmpItem = tmpItem.replace('{{platform}}', element.platform);
        tmpItem = tmpItem.replace('{{category}}', element.category);
        tmpItem = tmpItem.replace('{{rating}}', element.rating);
        tmpItem = tmpItem.replace('{{price}}', element.price);
        
        output+= tmpItem;
    });
    $(selector).html(output);

    $.each($(".item"), function(index, value) {

        value.style.backgroundImage = "url('./images/filter/" + softwareArray[index].img + "')";
    });
}