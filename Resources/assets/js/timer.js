function Timer(callback, delay) {

    var timerId, start, remaining = delay;

    this.pause = function() {

        clearTimeout(timerId);
        remaining -= new Date() - start;
      
    };

    this.resume = function() {
        if(remaining>0){
        	start = new Date();
        	timerId = setTimeout(callback, remaining);
        }
    };
    
    this.clear = function() {
    	
    	 clearTimeout(timerId);
    };

    this.resume();
}