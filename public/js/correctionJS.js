opos =
    [
        {
            "value": "2",
            "puntuacion": [10, -0.33, 0]
        },

        {
            "value": "4",
            "puntuacion": [20, -0.33, 0]
        },

        {
            "value": "5",
            "puntuacion": [30, -0.33, 0]
        },

        {
            "value": "6",
            "puntuacion": [40, -0.33, 0]
        },

        {
            "value": "7",
            "puntuacion": [50, -0.33, 0]
        },

        {
            "value": "8",
            "puntuacion": [60, -0.33, 0]
        },

        {
            "value": "10",
            "puntuacion": [70, -0.33, 0]
        },

        {
            "value": "11",
            "puntuacion": [80, -0.33, 0]
        },

        {
            "value": "0",
            "puntuacion": [0, 0, 0]
        }
    ];
scoreCorrectQuestions = $("[data-js='scoreCorrectQuestions']");
scoreIncorrectQuestions = $("[data-js='scoreIncorrectQuestions']");
scoreBlankQuestions = $("[data-js='scoreBlankQuestions']");

ncorrectas = $("[data-js='numberCorrectQuestions']");
nincorrectas = $("[data-js='numberIncorrectQuestions']");
nblanco = $("[data-js='numberBlankQuestions']");
ntotal = $("[data-js='totalQuestions']");

selection = $("[data-js='changeOppostionSelect']");

nota = $("[data-js='toPlaceMark']");
notaCol = $("[data-js='markCol']");

btnCorrect = $("[data-js='corregir']");


$(document).ready(function(){
    clear();
    startEvents();
    changeOpos();
    }
);

function clear() {
    selection.val("0");
    ncorrectas.val("0");
    nincorrectas.val("0");
    nblanco.val("0");
    scoreCorrectQuestions.val("0");
    scoreIncorrectQuestions.val("0");
    scoreBlankQuestions.val("0");
    ntotal.val("0");
    nota.val("");
}

function changeOpos() {
    for( i=0; i < opos.length; i++){
        if (selection.val() === opos[i]["value"]){
            scoreCorrectQuestions.val(opos[i]["puntuacion"][0]);
            scoreIncorrectQuestions.val(opos[i]["puntuacion"][1]);
            scoreBlankQuestions.val(opos[i]["puntuacion"][2]);
        }
    }
}

function corregir(){
    correccion.printResult();
}

function startEvents() {
    correccion.addListeners();

    btnCorrect.on( "click", function( event ) {
        corregir();
    });
    selection.on( "click", function( event ) {
        changeOpos();
    });

}

class Correction{

    scoreGood;
    scoreBad;
    scoreBlank;
    numGood;
    numBad;
    numBlank;

    totalQuestions;
    mark;

    constructor(){
        this.scoreGood = window.document.getElementById("score-good")
        this.scoreBad = window.document.getElementById("score-bad")
        this.scoreBlank = window.document.getElementById("score-blank")
        this.numGood = window.document.getElementById("num-good")
        this.numBad = window.document.getElementById("num-bad")
        this.numBlank = window.document.getElementById("num-blank")

        this.totalQuestions = window.document.getElementById("total-questions")
        this.mark = window.document.getElementById("final-mark");
    }

    checkScoresMakeSense() {
        if (this.numBad.value == 0 && this.numBlank.value == 0 && this.numGood.value == 0) return false;
        if (parseFloat(this.scoreBlank.value) > parseFloat(this.scoreGood.value)) return false;
        return true;
    }

    doCorrection(){
        if(!this.checkScoresMakeSense()) return false;
        let sum = this.scoreGood.value*(this.numGood.value) - this.scoreBad.value*(this.numBad.value) + this.scoreBlank.value*(this.numBlank.value);
        return sum;
    }

    printResult(){
        if (!this.doCorrection()){
            this.mark.innerHTML =  "Ha habido un error, comprueba movidas";
            return;
        }
        let score = this.doCorrection()
        const totalQ = parseFloat(this.numBlank.value) + parseFloat(this.numBad.value) +  parseFloat(this.numGood.value);
        let max = totalQ*parseFloat(this.scoreGood.value);
        this.mark.innerHTML = score + " / " + max;
    }

    addListeners(){
        const inputs = window.document.getElementsByClassName('exam-input');
        for(let i = 0; i < inputs.length; i++) {
            const input = inputs[i];
            input.addEventListener("focusout",  event => {
                let total = parseFloat(this.numBad.value) + parseFloat(this.numGood.value) + parseFloat(this.numBlank.value);
                this.totalQuestions.value = total;
            })
            input.addEventListener("focusout", event => {
                this.checkIllegalData(input)
            })
        }
    }
    checkIllegalData(input){
        if(input.id === 'score-blank' ) return;
        const value = parseFloat(input.value);
        if(value < 0){
            input.value = "0";
        }
    }

}

const correccion = new Correction();
