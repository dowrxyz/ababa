<template>
  <div class="modal" id="editAporte" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
      <div class="modal-content">
        <div class="modal-header bg-transparent">
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
          ></button>
        </div>
        <div class="modal-body pb-5 px-sm-5 pt-50">
          <div class="text-center mb-2">
            <h1 class="mb-1">Editar Aporte</h1>
          </div>
          <form
            id="editUserForm"
            class="row gy-1 pt-75"
            onsubmit="return false"
            v-on:submit.prevent="updateRegistro()"
          >
            <div class="col-12 col-md-6">
              <label class="form-label" for="modalEditUserFirstName"
                >Aporte</label
              >
              <input
                type="number"
                class="form-control"
                placeholder=""
                v-model="editando.aporte"
              />
            </div>
            <div class="col-12 col-md-6">
              <label class="form-label" for="modalEditUserStatus">Año</label>
              <select
                id="modalEditUserStatus"
                name="modalEditUserStatus"
                v-model="editando.ano"
                class="form-select"
                aria-label="Default select example"
              >
                <option value="0">Año</option>
                <option value="2019">2019</option>
                <option value="2020">2020</option>
                <option value="2021">2021</option>
                <option value="2022">2022</option>
              </select>
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label" for="modalEditUserStatus"
                >Tipo de Aporte</label
              >
              <select
                id="modalEditUserStatus"
                name="modalEditUserStatus"
                v-model="editando.tipo_aporte"
                class="form-select"
                aria-label="Default select example"
              >
                <option value="0">Tipo</option>
                <option value="APORTE">Aporte</option>
                <option value="INSCRIPCION">Inscripcion</option>
              </select>
            </div>

            <div class="col-12 text-center mt-2 pt-50">
              <button type="submit" class="btn btn-primary me-1">
                Guardar
              </button>
              <button
                type="reset"
                class="btn btn-outline-secondary"
                data-bs-dismiss="modal"
                aria-label="Cerrar"
              >
                Cerrar
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    props: ["editando"],
    data() {
      return {};
    },

    mounted() {},

    methods: {
      updateRegistro() {
        const params = {
          ano: this.editando.ano,
          tipo_aporte: this.editando.tipo_aporte,
          aporte: this.editando.aporte,
          id: this.editando.id,
        };

        axios
          .put("/api/aportes", params)
          .then((response) => {
            console.log(response.data);
            this.$emit("new");
            $("#editAporte").modal("hide");

            this.editando.aporte = "";
            this.editando.ano = 0;
            this.editando.tipo_aporte = 0;
          })
          .catch((e) => {
            const errorMesagge = e.response.data.message;
            if (errorMesagge == "insufficient permissions") {
              this.$notify.error({
                title: "Error!",
                message: "Permisos insuficientes",
                offset: 100,
              });
            }
          });
      },
    },
  };
</script>
