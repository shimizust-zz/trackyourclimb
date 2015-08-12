function sitePath() {
	if(this.result!=undefined){
        // we have already tested so return the stored value
        return this.result;
    }
    var folders=window.location.pathname.split('/');

    if(folders[1]=='climbtracker'){
        // we are inside local dev folder so return and store the folder name
        return this.result='/' + folders[1];
    }else{
        // we are in the production environment so store an empty string
        return this.result = '';
    }
}
