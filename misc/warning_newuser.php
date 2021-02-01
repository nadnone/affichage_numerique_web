
<style>
    .alert_newuser{

        width: 100%;
        height: 100%; 
        position: absolute; 
        display: flex;
        align-items: center;
        flex-direction: column;
        justify-content: center;
        background-color: var(--secondary_dark);
        color: var(--secondary);
    }
    .alert_newuser p{
        width: 60%;
        font-size: 18px;
    }


    @media screen and (max-width: 1200px){
        *{
            height: max-content;
        }
        .alert_newuser p{
            margin: 2% 0%;
            width: 96%;
            height: 100%;
            overflow-y: auto;
        }
    }

</style>

<div class="alert_newuser">

    <p id="data"></p>


    <script>
        document.getElementById("data").innerText = <?php echo json_encode(file_get_contents("./config/newuser.txt")); ?>
    </script>


    <div class="btn" onclick="window.location.href='./front/legal.html';">
        Mentions légales
    </div>

    <div class="btn" onclick="window.location.href='./index.php?notice=continue';">
        Continuer
    </div>



</div>