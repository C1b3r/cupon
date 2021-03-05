function cambiamenu(data)
{
   /* if(data.classList.contains("active"))
    {*/
       /* data.classList.remove("active");
        data.classList.add("fade");*/
        var divaocultar=data.getAttribute('data-attribute');
        switch (divaocultar) {
            case "config":
              /*   document.getElementById("listado").classList.remove("active");
               document.getElementById("listado").classList.add("fade");*/
                document.getElementById("link-config").classList.add("active");
                document.getElementById("link-listado").classList.remove("active");
              /*  document.getElementById("config").classList.remove("fade");
                document.getElementById("config").classList.add("active");*/
                document.getElementById("listado").style.display="none";
                document.getElementById("config").style.display="block";
                break;
            case "listado":
              /*   document.getElementById("config").classList.remove("active");
               document.getElementById("config").classList.add("fade");*/
               document.getElementById("link-config").classList.remove("active");
                document.getElementById("link-listado").classList.add("active");

               document.getElementById("listado").style.display="block";
               document.getElementById("config").style.display="none";
              /*  document.getElementById("listado").classList.remove("fade");
                document.getElementById("listado").classList.add("active");*/
                break;
        
          
        }
     //   console.log(divaocultar);
    /*}*/


}