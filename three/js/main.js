'use strict';

let startBtn = document.getElementById("start"),
	budgetValue = document.getElementsByClassName('budget-value')[0],
    dayBudgetValue = document.getElementsByClassName('daybudget-value')[0],
	levelValue = document.getElementsByClassName('level-value')[0],
	expensesValue = document.getElementsByClassName('expenses-value')[0],
	optionalExpensesValue = document.getElementsByClassName('optionalexpenses-value')[0],
	incomeValue = document.getElementsByClassName('income-value')[0],
    monthSavingsValue = document.getElementsByClassName('monthsavings-value')[0],
    yearSavingsValue = document.getElementsByClassName('yearsavings-value')[0],
    expensesItem = 
    document.getElementsByClassName('expenses-item'),
    expensesBtn = document.getElementsByTagName('button')[0],
    optionalExpenses = 
    document.getElementsByTagName('button')[1],
    countBudgetBtn = 
    document.getElementsByTagName('button')[2], 
    optionalExpensesItem = 
    document.querySelectorAll(".optionalexpenses-item"),
    incomeItem = document.querySelector('.choose-income'),
	checkSavings = document.querySelector('#savings'),
	sumValue = document.querySelector('.choose-sum'),
    percentValue = document.querySelector('.choose-percent'),
    yearValue = document.querySelector('.year-value'),
    monthValue = document.querySelector('.month-value'),
    dayValue = document.querySelector('.day-value');

let money, time;

startBtn.addEventListener('click', function() {
    console.log("НАЖАТИЕ");
    time = prompt("Введите дату в формате YYYY-MM-DD", "");
    money = +prompt("Ваш бюджет на месяц?", "");
  
  while (isNaN(money) || money == "" || money == null) {
    money = +prompt("Ваш бюджет на месяц?", "");
  }

  appData.wallet = money;
  appData.time = time;
  budgetValue.textContent = money;
  yearValue.value = new Date(Date.parse(time)).getFullYear();
  monthValue.value = new Date(Date.parse(time)).getMonth() + 1;
  dayValue.value = new Date(Date.parse(time)).getDate();   
});

countBudgetBtn.addEventListener('click', () => {
    if (appData.wallet != undefined) {
    appData.moneyPerDay = appData.wallet / 30;
    dayBudgetValue.textContent = appData.moneyPerDay.toFixed();
    if (appData.moneyPerDay < 1000) {
        levelValue.textContent = "Это минимальный уровень достатка!";
      } else if (appData.moneyPerDay > 1000 && appData.moneyPerDay < 2000) {
        levelValue.textContent = "Это средний уровень достатка!";
      } else if (appData.moneyPerDay >= 2000) {
        levelValue.textContent = "Это высокий уровень достатка!";
      } else {
        levelValue.textContent = "Ошибочка...!";
      }
    } else {
        dayBudgetValue.textContent = "Ошибочка...!";
    }
});

expensesBtn.addEventListener('click', function() {
    let sum = 0;
    for (let i = 0; i < expensesItem.length; i++) {
        let a = expensesItem[i].value,
            b = expensesItem[++i].value;
    
          if ((typeof (a) === "string") && typeof (a) != null && typeof (b) != null && a != "" && b != "" && a.length < 50) {
            appData.expenses[a] = b;
            sum += +b;
          } else {
            i--;
          }
        }
    expensesValue.textContent  = sum;
    
}); 

optionalExpenses.addEventListener('click', () => {
    for (let i = 0; i < optionalExpensesItem.length; i++) {
        optionalExpensesValue.textContent += optionalExpensesItem[i].value + ' ';
        appData.optionalExpenses += optionalExpensesItem[i];
    }
});

incomeItem.addEventListener('input', () => {
    let items = incomeItem.value;
    appData.income = items.split(", ");
    incomeValue.textContent = appData.income;
});

checkSavings.addEventListener("click", () => {
    if (appData.savings == true) {
        appData.savings = false;
    } else {
        appData.savings = true;
    }
});

sumValue.addEventListener('click', () => {
    if (appData.savings == true) {
        let sum = +sumValue.value;
        let percent = +percentValue.value;
        appData.monthIncome = sum/100/12*percent;
        appData.yearIncome = sum/100*percent;
        monthSavingsValue.textContent = appData.monthIncome.toFixed(1);
        yearSavingsValue.textContent = appData.yearIncome.toFixed(1);
    }
});

percentValue.addEventListener('click', () => {
    if (appData.savings == true) {
        let sum = +sumValue.value;
        let percent = +percentValue.value;
        appData.monthIncome = sum/100/12*percent;
        appData.yearIncome = sum/100*percent;
        monthSavingsValue.textContent = appData.monthIncome.toFixed(1);
        yearSavingsValue.textContent = appData.yearIncome.toFixed(1);
    }
});

let appData = {
  wallet: money,
  timeData: time,
  expenses: {},
  optionalExpenses: {},
  income: [],
  saving: true,
};

    
