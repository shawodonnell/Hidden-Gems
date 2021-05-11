var sideMenuOpen = document.getElementById("slideMenuButton");
var closeMenu = document.getElementById("Close");

async function resultsMarkUp() {
    
    var title = document.getElementsByName("titleid");
    var parent = document.getElementsByName("posterw154");  
    var movieforms = document.getElementsByName("movieform");

    for(i=0; i<(title.length); i++){
        
        try {
            var key = sessionStorage.getItem("TMDB");
            
            var ref = title[i].value;  
            const findURL = `https://api.themoviedb.org/3/find/${ref}?api_key=${key}&language=en-US&external_source=imdb_id`;
            
            const response = await fetch(findURL);
            const data = await response.json();  

            //FILLING IN TMDB ID Form Field
            idValue = data.movie_results[0].id;
            var idField = document.createElement("input");
            idField.setAttribute("type", "hidden");
            idField.setAttribute("name", "TMDBid");
            idField.setAttribute("id", "TMDBid");
            idField.setAttribute("value", idValue);
            var movieform = movieforms[i];
            movieform.appendChild(idField);

            //FILLING IN IMG DIV
            const poster = data.movie_results[0].poster_path;
            if(!poster){
                image = "http://sodonnell26.lampt.eeecs.qub.ac.uk/assignment/project/css/resources/defaultposter.jpg";
            } else {
                image = `http://image.tmdb.org/t/p/w154${poster}`;                    
            }            
            var img = document.createElement('img')
            img.src = image
            var div = parent[i];
            div.appendChild(img)
            
        } catch (error) {    
            if(error instanceof TypeError)       {
                image = "http://sodonnell26.lampt.eeecs.qub.ac.uk/assignment/project/css/resources/defaultposter.jpg";
                var img = document.createElement('img')
                img.src = image
                var div = parent[i];
                div.appendChild(img)
            }
            console.log(error);
            
        }        
    }
}
function toggleSlideMenu() {
    document.getElementById("slideSideMenu").classList.toggle('open');
    document.getElementById("slideUnderLayer").classList.toggle('open');
}

sideMenuOpen.addEventListener('click',toggleSlideMenu);
closeMenu.addEventListener('click',toggleSlideMenu);
window.addEventListener('load', ()=>{  
resultsMarkUp(); 
document.getElementById('slideUnderLayer').removeAttribute("hidden");
});