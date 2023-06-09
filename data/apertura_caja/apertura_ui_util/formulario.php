<style>
    #cmp_apertura_container {
        background-color: rgb(0, 0, 0, 0.4);
        display: flex;
        justify-content: center;
        height: 100vh;
    }

    #cmp_apertura_form {
        align-self: center;
        background-color: #E0E0E0;
        opacity: 1;
        width: 500px;
        height: 60vh;
        padding: 15px;
        overflow-y: auto;
    }

    #cmp_apertura_prod_list {
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
    }

    .cmp_apertura_req {
        color: red;
    }
</style>
<div id="cmp_apertura_container">
    <form id="cmp_apertura_form">
        <h3 style="text-align: center; opacity: 1;">APERTURA DE CAJA</h3>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="">MONTO DE APERTURA: <span class="requ">*</span></label>
                    <div class="input-group">
                        <span class="input-group-addon">$</span>
                        <input id="cmp_apertura_monto" name="monto" required type="text" class="form-control" placeholder="0.00">
                    </div>
                    <label for="">OBSERVACIONES: <span class="requ">*</span></label>
                    <textarea required id="cmp_apertura_obs" name="observacion" class="form-control" name="" cols="30" rows="5" placeholder="INGRESE OBSERVACIONES"></textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <button id="cmp_apertura_guardar" class="btn btn-success" type="button">ABRIR CAJA</button>
            </div>
        </div>
    </form>
</div>