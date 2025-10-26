class CustomSlider {

    constructor(className) {
        this.className = className;
        this.element = document
            .getElementsByClassName(className)[0]
            .getElementsByClassName("slick-track")[0];
        this.numberOfSlides = this.element.querySelectorAll(".slick-slide").length;
        this.width = 0;
        this.pas = 0;
        this.slidesToShow = 3;
        this.currentTranslate = 0;
        this.itemName = this.className.replace(this.className.split("-",2)[1],"item");
        this.pickWidth();
    }
    pickWidth() {
        switch (this.className) {
            case "winner-wrap":
                if (window.innerWidth < 1500) {
                    this.pas = 124;
                    if (window.innerWidth < 1400) {
                        this.pas = 115;
                        if (window.innerWidth < 1200) {
                            this.pas = 170;
                            this.slidesToShow = 2;
                        }
                    }
                }
                else {
                    this.pas = 152;
                }
                break;
            case "gallery-area":
                console.log(this.numberOfSlides++);
                this.slidesToShow=5;
                if (window.innerWidth < 1500) {
                    this.pas = 680;
                        if (window.innerWidth < 1200) {
                            this.pas = 1032;
                            this.slidesToShow = 1;
                        }
                    
                }
                else {
                    this.pas = 904;
                }
                this.currentTranslate = -5 * this.pas;
                break;
        }
        this.width = this.pas * this.numberOfSlides;
        this.element.style.width = this.width + "px";
        this.element.style.transform = "translate3d(" + this.currentTranslate + "px, 0px, 0px)";
        this.element.querySelectorAll("."+this.itemName).forEach(
            (x) => (x.style = "width:" + this.pas + "px")
        );
    }


    changeSlider(direction) {
        this.width = parseInt(this.element.style.width.replace("px", ""));
        this.pas = this.width / this.numberOfSlides;
        this.currentTranslate += direction == "left" ? this.pas : -this.pas;
        if (this.currentTranslate >= this.pas) {
            this.currentTranslate = (this.slidesToShow - this.numberOfSlides) * this.pas;
        } else if (this.currentTranslate < (this.slidesToShow - this.numberOfSlides) * this.pas) {
            this.currentTranslate = 0;
        }
        let val = this.element.style.transform.split("px", 1)[0].split("(", 2)[1];
        this.element.style.transform = this.element.style.transform.replace(val, this.currentTranslate);
    }
}