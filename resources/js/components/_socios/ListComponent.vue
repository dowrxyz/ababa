<template>
  <div class="card">
    <div class="card-header text-right">
      <div>
        <div>
          <h4 class="card-title">Total Socios : {{ pagination.total }}</h4>
        </div>
        <div>2023</div>
      </div>
      <button class="btn btn-primary float-right" v-on:click="crear()">
        <i class="fa fa-plus"></i> Agregar Nuevo
      </button>
      <button class="btn btn-danger float-right" v-on:click="descargar()">
        <i class="fa fa-list"></i> Exportar
      </button>
    </div>
    <el-row style="margin-left: 20px; margin-right: 20px" :gutter="10">
      <el-col :span="4">
        <el-input
          @keyup.enter.native="Search"
          @clear="Search"
          size="small"
          placeholder="Documento"
          prefix-icon="el-icon-search"
          v-model="documento"
          clearable
          class="w-100"
        >
        </el-input>
      </el-col>

      <el-col :span="4">
        <el-input
          @keyup.enter.native="Search"
          @clear="Search"
          size="small"
          placeholder="Nombre"
          prefix-icon="el-icon-search"
          v-model="nombre"
          clearable
          class="w-100"
        >
        </el-input>
      </el-col>

      <el-col :span="4">
        <el-input
          @keyup.enter.native="Search"
          @clear="Search"
          size="small"
          placeholder="No. Cuenta bancaria"
          prefix-icon="el-icon-search"
          v-model="cuentaBancaria"
          clearable
          class="w-100"
        >
        </el-input>
      </el-col>

      <el-col :span="4">
        <el-input
          @keyup.enter.native="Search"
          @clear="Search"
          size="small"
          placeholder="No. Telefono"
          prefix-icon="el-icon-search"
          v-model="telefono"
          clearable
          class="w-100"
        >
        </el-input>
      </el-col>

      <el-col :span="4">
        <el-select
          class="w-100"
          v-model="usuario"
          placeholder="Usuarios"
          @change="Search()"
        >
          <el-option
            v-for="item in usuarios"
            :key="item.id"
            :label="item.user"
            :value="item.id"
          >
            <span style="float: left">{{ item.user }}</span>
          </el-option>
        </el-select>
      </el-col>

      <el-col :span="4">
        <el-select
          class="w-100"
          v-model="pago"
          placeholder="Tipo de pago"
          @change="Search()"
        >
          <el-option
            v-for="item in pagos"
            :key="item.id"
            :label="item.nombre"
            :value="item.id"
          >
            <span style="float: left">{{ item.nombre }}</span>
          </el-option>
        </el-select>
      </el-col>
    </el-row>

    <el-table :data="socios" stripe style="width: 100%">
      <div slot="empty">
        <span
          >No se encontraron resultados <el-icon name="search"></el-icon
        ></span>
      </div>
      <el-table-column type="expand">
        <template slot-scope="props">
          <el-row
            style="margin-left: 20px; margin-right: 20px; margin-bottom: 20px"
            :gutter="20"
          >
            <el-col :span="5">
              <el-button
                type="success"
                @click="registrosSocio(props.row.id, props.row.nombres)"
              >
                <i class="fa fa-list"></i> Mostrar Registro de
                Aportes</el-button
              >
            </el-col>
            <el-col :span="4">
              <el-button
                style="width: 100%"
                type="primary"
                @click="editarSocio(props.row.id)"
              >
                <i class="fa fa-edit"></i> Editar Usuario</el-button
              >
            </el-col>
            <el-col :span="4">
              <el-button
                style="width: 100%"
                type="primary"
                @click="generatePartnerFile()"
              >
                <i class="fa fa-user"></i> Ficha Del Usuario</el-button
              >
            </el-col>
            <el-col :span="4">
              <el-button
                style="width: 100%"
                type="primary"
                @click="dsecargarArchivo(props.row.documento_pdf.url)"
              >
                <i class="fa fa-file-pdf"></i>
                Documento</el-button
              >
            </el-col>
            <el-col :span="4">
              <el-button
                style="width: 100%"
                type="primary"
                @click="aporteClick(props.row.id, props.row.banco.id)"
              >
                <i class="fa fa-plus-circle"></i> Nuevo Aporte</el-button
              >
            </el-col>
            <el-col :span="5" style="margin-top: 10px">
              <el-button
                style="width: 100%"
                type="primary"
                @click="
                  dsecargarDocumento(
                    '/documentos_descarga/2.-_FORMULARIO_ACTUALIZACION_DE_DATOS_-__10__OCT._2023.docx',
                    'FORMULARIO_ACTUALIZACION_DE_DATOS_-__10__OCT._2023'
                  )
                "
              >
                <i class="fa fa-file-pdf"></i> Actualización De Datos</el-button
              >
            </el-col>
            <el-col :span="4" style="margin-top: 10px">
              <el-button
                style="width: 100%"
                type="primary"
                @click="
                  dsecargarDocumento(
                    '/documentos_descarga/3.-_FORMULARIO_SOLICITUD_DESCUENTO_ISSFA,_10_OCT.__2023.docx',
                    'FORMULARIO_SOLICITUD_DESCUENTO_ISSFA,_10_OCT.__2023'
                  )
                "
              >
                <i class="fa fa-file-pdf"></i> Descuento Del ISSFA</el-button
              >
            </el-col>

            <el-col :span="4" style="margin-top: 10px">
              <el-button
                style="width: 100%"
                type="primary"
                @click="
                  dsecargarDocumento(
                    '/documentos_descarga/1.-_FORMULARIO_SOCIO_NUEVOS_10_OCT.__2023.docx',
                    'FORMULARIO_SOCIO_NUEVOS_10_OCT.__2023.pdf'
                  )
                "
              >
                Afiliación</el-button
              >
            </el-col>
          </el-row>

          <el-row style="margin-left: 20px; margin-right: 20px">
            <el-descriptions class="margin-top" :column="2" border>
              <el-descriptions-item scope="props">
                <template slot="label">
                  <i class="el-icon-user"></i>
                  Cedula
                </template>
                {{ props.row.documento }}
              </el-descriptions-item>
              <el-descriptions-item scope="props">
                <template slot="label">
                  <i class="el-icon-wallet"></i>
                  Cuenta
                </template>
                {{ props.row.cuenta }}
              </el-descriptions-item>
              <el-descriptions-item scope="props">
                <template slot="label">
                  <i class="el-icon-date"></i>
                  Fecha de Registro
                </template>
                {{ props.row.created_at }}
              </el-descriptions-item>
              <el-descriptions-item scope="props">
                <template slot="label">
                  <i class="el-icon-date"></i>
                  Ultima Actualizacion
                </template>
                {{ props.row.updated_at }}
              </el-descriptions-item>
            </el-descriptions>
          </el-row>
        </template>
      </el-table-column>
      <el-table-column prop="nombres" label="Nombre"> </el-table-column>
      <el-table-column prop="banco.siglas" label="Banco"> </el-table-column>
      <el-table-column prop="saldo" label="Saldo">
        <template slot-scope="scope"> $ {{ scope.row.saldo }} </template>
      </el-table-column>
      <el-table-column prop="pago" label="Pago">
        <template slot-scope="scope">
          <el-tag :type="scope.row.pago.color" disable-transitions>{{
            scope.row.pago.value
          }}</el-tag>
        </template>
      </el-table-column>

      <el-table-column prop="estado" label="Estado">
        <template slot-scope="scope">
          <el-tag :type="scope.row.estado.color" disable-transitions>{{
            scope.row.estado.value
          }}</el-tag>
        </template>
      </el-table-column>
      <el-table-column prop="creador.user" label="Creado por">
      </el-table-column>
    </el-table>

    <nav aria-label="Page navigation example">
      <ul class="pagination justify-content-center">
        <li class="page-item" v-if="pagination.current_page > 1">
          <a
            class="page-link"
            href="#"
            @click.prevent="changePage(pagination.current_page - 1)"
            >Atrás</a
          >
        </li>
        <li
          class="page-item"
          v-for="page in pagesNumber"
          :key="page"
          v-bind:class="[page == isActived ? 'active' : '']"
        >
          <a class="page-link" href="#" @click.prevent="changePage(page)">
            {{ page }}
          </a>
        </li>
        <li
          class="page-item"
          v-if="pagination.current_page < pagination.last_page"
        >
          <a
            class="page-link"
            href="#"
            @click.prevent="changePage(pagination.current_page + 1)"
            >Siguiente</a
          >
        </li>
      </ul>
    </nav>
  </div>
</template>

<script>
  export default {
    props: ["socios", "pagination"],
    data() {
      return {
        estados: [
          { id: 1, nombre: "Activo", color: "success" },
          { id: 2, nombre: "Inactivo", color: "danger" },
          { id: 3, nombre: "Bloqueado", color: "warning" },
          { id: 4, nombre: "Fallecido", color: "danger" },
        ],
        bancos: [
          { id: 0, nombre: "Todos" },
          { id: 1, nombre: "Bajo Convenio" },
          { id: 2, nombre: "Sin Convenio" },
        ],
        pagos: [
          { id: 1, nombre: "Cancelado" },
          { id: 2, nombre: "Pendiente" },
        ],
        documento: "",
        nombre: "",
        estado: "",
        banco: "",
        socio: "",
        descargando: "",
        usuarios: [],
        telefono: "",
        cuentaBancaria: "",
        usuario: "",
        pago: "",
        offset: 5,
      };
    },

    mounted() {
      axios.get("/api/usuariosAdmin").then((response) => {
        this.usuarios = response.data;
      });
    },
    computed: {
      isActived: function () {
        return this.pagination.current_page;
      },
      pagesNumber: function () {
        if (!this.pagination.to) {
          return [];
        }

        var from = this.pagination.current_page - this.offset;
        if (from < 1) {
          from = 1;
        }

        var to = from + 2 * this.offset;
        if (to > this.pagination.last_page) {
          to = this.pagination.last_page;
        }

        var pagesArray = [];
        while (from <= to) {
          pagesArray.push(from);
          from++;
        }
        return pagesArray;
      },
    },
    methods: {
      changePage(page) {
        const params = {
          documento: this.documento,
          nombre: this.nombre.toUpperCase(),
          cuentaBancaria: this.cuentaBancaria,
          telefono: this.telefono,
          usuario: this.usuario,
          pago: this.pago,
        };
        this.$emit("changePage", page, params);
      },
      Search() {
        const params = {
          documento: this.documento,
          nombre: this.nombre.toUpperCase(),
          cuentaBancaria: this.cuentaBancaria,
          telefono: this.telefono,
          usuario: this.usuario,
          pago: this.pago,
        };
        this.$emit("search", 1, params);
      },
      crear() {
        this.$emit("crear");
      },
      filterTag(id, row) {
        return row.estado === id;
      },
      registrosSocio(id_socio, nombres) {
        this.$emit("listAportes", id_socio, nombres);
      },
      dsecargarArchivo(url) {
        axios({
          url: url,
          method: "GET",
          responseType: "blob",
        })
          .then((response) => {
            var fileURL = window.URL.createObjectURL(new Blob([response.data]));
            var fileLink = document.createElement("a");

            fileLink.href = fileURL;
            fileLink.setAttribute("download", "documento.pdf");
            document.body.appendChild(fileLink);

            fileLink.click();
          })
          .catch((e) => {
            console.error(e);
            if (e.message == "Request failed with status code 404")
              alert("Documento no encontrado");
          });
      },
      editarSocio(id) {
        console.log(id);

        axios.get("/api/getSocio/" + id).then((response) => {
          var socio = response.data;
          this.$emit("editar", socio);
        });
      },
      aporteClick(id, banco_id) {
        var socio_id = id;
        this.$emit("createAporte", socio_id, banco_id);
      },
      async descargar() {
        const params = {
          documento: this.documento,
          nombre: this.nombre.toUpperCase(),
          cuentaBancaria: this.cuentaBancaria,
          telefono: this.telefono,
          usuario: this.usuario,
          pago: this.pago,
        };
        console.log("ejecutando");
        console.log(params);

        //axios.post('/socios/buscar?page='+page, params).then((response) =>
        await axios.post("/export_socios", params).then((response) => {
          this.descargando = response.data;
          console.log(response.data);
        });

        const url = "/documentos_imagenes/socios_Excels/" + this.descargando;

        await axios({
          url: url,
          method: "GET",
          responseType: "blob",
        }).then((response) => {
          const fileURL = window.URL.createObjectURL(new Blob([response.data]));
          const fileLink = document.createElement("a");

          fileLink.href = fileURL;
          fileLink.setAttribute("download", this.descargando);
          document.body.appendChild(fileLink);

          fileLink.click();
        });
      },
      dsecargarDocumento(data, name) {
        axios({
          url: data,
          method: "GET",
          responseType: "blob",
        }).then((response) => {
          var fileURL = window.URL.createObjectURL(new Blob([response.data]));
          var fileLink = document.createElement("a");

          fileLink.href = fileURL;
          fileLink.setAttribute("download", `${name}.docx`);
          document.body.appendChild(fileLink);

          fileLink.click();
        });
      },

      generatePartnerFile() {
        axios({
          url: "http://127.0.0.1:8000/api/generatePartnerFile",
          method: "post",
        })
          .then(async (response) => {
            this.dsecargarArchivo(response.data);
          })
          .catch((e) => {
            console.error(e);
            if (e.message == "Request failed with status code 404")
              alert("Documento no encontrado");
          });
      },
    },
  };
</script>
