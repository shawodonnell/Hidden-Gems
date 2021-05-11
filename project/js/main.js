
var sideMenuOpen = document.getElementById("slideMenuButton");
var closeMenu = document.getElementById("Close");
var startbutton = document.getElementById("nextSection");

async function getTMDB(){   
    try {
        const TMDBurl = "http://sodonnell26.lampt.eeecs.qub.ac.uk/assignment/project/api/tmdb.php";
        const response = await fetch(TMDBurl);
        const data = await response.json();
        sessionStorage.setItem("TMDB",data['TMDBkey']);
        console.log(sessionStorage.getItem("TMDB"));
    } catch (error) {
        console.log(error);
    }
}

async function getUpcoming() {
    TMDB = sessionStorage.getItem("TMDB");
    page = Math.floor((Math.random()*15)+1);

    try {    
    console.log(page);
    const url = `https://api.themoviedb.org/3/movie/upcoming?api_key=${TMDB}&language=en-US&page=${page}&region=US`
    
    const response = await fetch(url);
    const data = await response.json();  
    insertUpcoming(data)
        
    } catch (error) {
        let page=1;
        const url = `https://api.themoviedb.org/3/movie/upcoming?api_key=${TMDB}&language=en-US&page=${page}&region=US`
    
        const response = await fetch(url);
        const data = await response.json();  
        console.log(data);
        insertUpcoming(data)

    }
    
}
function insertUpcoming(data){    
    document.getElementById("uph2").removeAttribute("hidden");
    for (let i = 0; i <= 2; i++) {
        rand = Math.floor((Math.random()*data.results.length)+1);
        movie = data.results[rand].title;
        date = data.results[rand].release_date;          
        tile = document.getElementById(`upcoming${i}`);
        tile.innerHTML = `<p>${movie}</p>coming ${date}`;
        
    } 
}

async function getTrending(){
    try {
        let TMDB = sessionStorage.getItem("TMDB");     
        let page = Math.floor((Math.random()*15)+1);   
        const url = `https://api.themoviedb.org/3/trending/movie/week?api_key=${TMDB}&page=${page}`;
        
        const response = await fetch(url);
        const data = await response.json();  
        insertTrending(data);
            
    } catch (error) {
        let TMDB = sessionStorage.getItem("TMDB");     
        let page = 1;   
        const url = `https://api.themoviedb.org/3/trending/movie/week?api_key=${TMDB}&page=${page}`;
        
        const response = await fetch(url);
        const data = await response.json();  
        insertTrending(data);
    }
    
}

function insertTrending(data){
    document.getElementById("trh2").removeAttribute("hidden");
    for (let i = 0; i <= 5; i++) {
        rand = Math.floor((Math.random()*data.results.length)+1);
        movie = data.results[rand].title;
        var ul = document.getElementById("trendlist");
        var li = document.createElement("li");
        li.innerHTML = `${movie}`;
        ul.appendChild(li);   
    } 
}

function toggleSlideMenu() {
    document.getElementById("slideSideMenu").classList.toggle('open');
    document.getElementById("slideUnderLayer").classList.toggle('open');
}


getTMDB();
getUpcoming();
getTrending();

// EVENT LISTENERS

sideMenuOpen.addEventListener('click',toggleSlideMenu);
closeMenu.addEventListener('click',toggleSlideMenu);
window.addEventListener('load', ()=>{    
document.getElementById('slideUnderLayer').removeAttribute("hidden");
    if(sessionStorage.getItem('loggedin')==true) {
        document.getElementById('welcome').setAttribute("hidden","true");
    }
});


