const tireArray = new Array(
  {
    id: 0,
    name: "30",
    os: "Windows",
    ram: "2",
    ssd: "250",
  },
  {
    id: 1,
    name: "60",
    os: "Windows",
    ram: "8",
    ssd: "500",
  },
  {
    id: 2,
    name: "90",
    os: "Windows",
    ram: "2",
    ssd: "500",
  },
  {
    id: 3,
    name: "120",
    os: "Linux",
    ram: "16",
    ssd: "1000",
  },
  {
    id: 4,
    name: "150",
    os: "Linux",
    ram: "16",
    ssd: "500",
  },
  {
    id: 5,
    name: "180",
    os: "Windows",
    ram: "2",
    ssd: "60",
  },
  {
    id: 6,
    name: "210",
    os: "Linux",
    ram: "2",
    ssd: "250",
  },
  {
    id: 7,
    name: "240",
    os: "Windows",
    ram: "8",
    ssd: "250",
  },
  {
    id: 8,
    name: "270",
    os: "Linux",
    ram: "2",
    ssd: "60",
  }
);

const propertyNames = ["os", "ram", "ssd"];

const properties = {
  os: ["Windows", "Linux"],
  ram: ["16", "8", "2"],
  ssd: ["1000", "500", "250", "60"],
};

//Этот скрипт вызовется, когда документ полностью загружен
$("document").ready(function () {
  //Находим наш див, в котором будут карточки лежать, он заранее сверстан
  var container = document.getElementById("card-container");
  var filter = $(".filter");
  //Вызываем скрипт, когда документ полностью загрузится
  addTires(tireArray, propertyNames, container);
  addCheckboxes(properties, filter);

  var cardArray = document.getElementsByClassName("card");
  var checkBoxArray = $('input[type="checkbox"]');

  for (var checkBox of checkBoxArray) {
    $(checkBox).prop("checked", false);
    $(checkBox).prop("disabled", false);
  }

  $('button[class="reset-btn"]').click(function () {
    disableUselessCheckBoxes(cardArray, checkBoxArray, propertyNames);

    for (var checkBox of checkBoxArray) {
      $(checkBox).prop("checked", false);
      $(checkBox).prop("disabled", false);
    }

    applyFilters(checkBoxArray, cardArray);
  });

  disableUselessCheckBoxes(cardArray, checkBoxArray, propertyNames);

  applyFilters(checkBoxArray, cardArray);

  for (let checkBox of checkBoxArray) {
    $(checkBox).change(function (event) {
      applyFilters(checkBoxArray, cardArray);
    });
  }
});

function applyFilters(checkBoxArray, cardArray) {
  let checkedCheckBoxes = new Map();

  //Добавляем по каждому параметру актуальные выбранные значения
  for (const checkBox of checkBoxArray) {
    if (checkBox.checked) {
      if (!checkedCheckBoxes.has(checkBox.name)) {
        checkedCheckBoxes.set(checkBox.name, [checkBox.value]);
      } else {
        checkedCheckBoxes.get(checkBox.name).push(checkBox.value);
      }
    }
  }

  let showThis = [];

  if (checkedCheckBoxes.size > 0) {
    showThis = giveMeArray(checkedCheckBoxes, cardArray);

    for (const checkBox of checkBoxArray) {
      if (true) {
        var newIds = checkthisParameter(
          checkBox,
          propertyNames,
          checkBoxArray,
          cardArray
        );

        if (newIds.length == 0) {
          $(checkBox).prop("disabled", true);
        } else if (compare(showThis, newIds)) {
          $(checkBox).prop("disabled", true);
        } else {
          $(checkBox).prop("disabled", false);
        }
      }
    }
  } else {
    disableUselessCheckBoxes(cardArray, checkBoxArray, propertyNames);

    for (const card of cardArray) {
      showThis.push($(card).data("id"));
    }
  }

  showCards(showThis, cardArray);
}

function showCards(cardIdArray, cardArray) {
  for (const card of cardArray) {
    $(card).addClass("hide");

    let id = $(card).data("id");

    if (cardIdArray.includes(id)) {
      $(card).removeClass("hide");
    }
  }
}

function checkthisParameter(
  checkBoxToCheck,
  properties,
  checkBoxArray,
  cardArray
) {
  let checkedCheckBoxes = new Map();

  for (const checkBox of checkBoxArray) {
    if (checkBox.checked) {
      if (!checkedCheckBoxes.has(checkBox.name)) {
        checkedCheckBoxes.set(checkBox.name, [checkBox.value]);
      } else {
        checkedCheckBoxes.get(checkBox.name).push(checkBox.value);
      }
    }
  }

  //Добавляем или удаляем, в зависимости от состояния чекбокса (был выбран или нет)

  if (checkBoxToCheck.checked) {
    let index = checkedCheckBoxes
      .get(checkBoxToCheck.name)
      .indexOf(checkBoxToCheck.value);
    if (index > -1) {
      checkedCheckBoxes.get(checkBoxToCheck.name).splice(index, 1);
    }
  } else {
    if (checkedCheckBoxes.has(checkBoxToCheck.name)) {
      checkedCheckBoxes.get(checkBoxToCheck.name).push(checkBoxToCheck.value);
    } else {
      checkedCheckBoxes.set(checkBoxToCheck.name, [checkBoxToCheck.value]);
    }
  }

  for (const [key, value] of checkedCheckBoxes) {
    if (checkedCheckBoxes.get(key).length == 0) {
      checkedCheckBoxes.delete(key);
    }
  }

  if (checkedCheckBoxes.size == 0) {
    let allCardIds = [];

    for (const card of cardArray) {
      allCardIds.push(card.dataset.id);
    }

    return allCardIds;
  }

  return giveMeArray(checkedCheckBoxes, cardArray);
}

//Выдает массив идентификаторов тех карточек, которые подходят под набор чекбоксов
function giveMeArray(mapWithCheckedCheckBoxes, cardArray) {
  for (const card of cardArray) {
    $(card).data("match", true);
  }

  let newCardArray = [];

  let mapWithCards = new Map();

  for (const [parameter, values] of mapWithCheckedCheckBoxes.entries()) {
    for (const card of cardArray) {
      //Если наша карточка подходит под выбранные характеристики
      if (values.includes("" + $(card).data(parameter))) {
        if (mapWithCards.has(card)) {
          mapWithCards.get(card).push($(card).data(parameter));
        } else {
          mapWithCards.set(card, [$(card).data(parameter)]);
        }
      }
    }
  }

  //Тут странно
  for (const [key, value] of mapWithCards.entries()) {
    if (value.length == mapWithCheckedCheckBoxes.size) {
      newCardArray.push($(key).data("id"));
    }
  }

  newCardArray.sort(function (a, b) {
    return a - b;
  });

  return newCardArray;
}

function compare(a1, a2) {
  return a1.length == a2.length && a1.every((v, i) => v === a2[i]);
}

function disableUselessCheckBoxes(cardArray, checkBoxArray, properties) {
  for (var checkBox of checkBoxArray) {
    $(checkBox).prop("disabled", true);
  }

  for (const card of cardArray) {
    for (const property of properties) {
      let name = property;
      let value = $(card).data(property);

      $(`input[type="checkbox"][name="${name}"][value="${value}"]`).prop(
        "disabled",
        false
      );
    }
  }
}

function uncheckWrongCheckBoxes(
  cardIdArray,
  cardArray,
  checkBoxArray,
  properties
) {
  for (const card of cardArray) {
    let id = $(card).data("id");

    if (!cardIdArray.includes(id)) {
      for (const property of properties) {
        let name = property;
        let value = $(card).data(property);

        var checkBox = $(
          `input[type="checkbox"][name="${name}"][value="${value}"]`
        );

        $(checkBox).prop("checked", false);
      }
    }
  }
}

//контейнер - тот див, куда вставляются карточки
function addTires(array, properties, container) {
  //Массив с данными
  for (const key in array) {
    var tire = array[key];
    //создание элемента
    var newCard = document.createElement("div");

    //Добавление класса со стилями, который описывает внешний вид карточки
    newCard.className = "card";

    //здесь создаются и устанавливаются data-аттрибуты, заполняются данными из массива с карточками
    newCard.dataset.id = tire.id;
    newCard.dataset.name = tire.name;

    for (const property of properties) {
      $(newCard).attr("data-" + property, tire[property]);
    }
    newCard.dataset.match = true;

    //html, который будет соответствовать этой карточке
    //Обрати внимание на кавычки у строки, они находятся на букве Ё
    let a = `<div class="price-block"><div class="price-block__price">${tire.name}$
    </div><div class="price-txt">${tire.os}
    </div> <div class="price-txt">${tire.ram}
    </div><div class="price-txt">${tire.ssd}
    </div></div>`;

    //Это обязательно, иначе он не понимает такую строку
    //Мы вставляем заготовленный html в карточку
    newCard.innerHTML = "" + a;

    //говорим контейнеру добавить карточку в конец
    container.append(newCard);
  }
}

function addCheckboxes(properties, container) {
  for (const property in properties) {
    var subsection = document.createElement("div");

    $(subsection).addClass("chkboxs");

    subsection.innerText = property.charAt(0).toUpperCase() + property.slice(1);

    for (const value of properties[property]) {
      var elem = document.createElement("label");

      elem.innerText = value.charAt(0).toUpperCase() + value.slice(1);

      var newCheckBox = document.createElement("input");

      $(newCheckBox).attr({
        type: "checkbox",
        name: property,
        value: value,
      });

      elem.prepend(newCheckBox);

      subsection.append(elem);
    }

    container.append(subsection);
  }
}
