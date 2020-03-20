public ContentResult DetallePorVendedoresToExcel(int mesInicio, int yearInicio, int mesFinal, int yearFinal)
        {
            DateTime fechaInicio = new DateTime(yearInicio, mesInicio, 1);
            if (mesFinal == 12)
            {
                mesFinal = 1;
                yearFinal++;
            }
            else
            {
                mesFinal++;
            }
            DateTime fechaFinal = new DateTime(yearFinal, mesFinal, 1);

            var ds = ELVentas.GenerarExcel_Detalle_Por_Vendedores(fechaInicio, fechaFinal);
            DataTable dtv = ds.Tables[0];  //Aqui viene el listado 

            if (dtv == null || dtv.Rows.Count == 0)
            {
                SiteUtils.EscribirLog("GenerarExcelDetalleGeneral retorna datatable nula o vacía");
                return null;
            }
            //tenemos que crear un list de los depositantes (distintos)
            List<string> depositantes = new List<string>();
            depositantes.Add(Convert.ToString(dtv.Rows[0]["Nombre"]));

            for (int i = 1; i < dtv.Rows.Count; i++)
            {
                var nuevoDepositante = Convert.ToString(dtv.Rows[i]["Nombre"]);
                if (!depositantes.Contains(nuevoDepositante)) {
                    depositantes.Add(nuevoDepositante);
                }
            }

            int maxDepositantes = depositantes.Count;
            //Creamos la tabla
            DataTable tablaExcel = CrearTablaDetallePorVendedores();

            var comision = 0m;
            var gastosEnvio = 0m;
            var tienda = "";
            // Primer depositante
            var DepositanteActivoControl = depositantes[0];
            var DepositanteActivo = "";
            var importeOp = 0m;
            var sumatorioImportes = 0m;
            var sumatorioComisiones = 0m;
            var sumatorioGastos = 0m;
            var sumatorioMargenDepositante = 0m;
            var sumatorioMargenDepositario = 0m;

            //Encabezado para el vendedor
            var myDataRow = tablaExcel.NewRow();
            myDataRow["Depositante"] = "";
            myDataRow["Titulo"] = " ";
            myDataRow["Referencia"] = "";
            myDataRow["Fecha"] = "";
            myDataRow["Precio"] = "";
            myDataRow["PuntoVenta"] = "";
            myDataRow["Comision"] = "";
            myDataRow["MargenDepositante"] = "";
            myDataRow["MargenDepositario"] = "";
            myDataRow["GastosEnvio"] = "";
            tablaExcel.Rows.Add(myDataRow);

            string DepositanteActivoTM = "";
            DepositanteActivoTM = Convert.ToString(dtv.Rows[0]["Nombre"]);
            int maxArticulos = dtv.Rows.Count;
            //Bucle depositantes con articulos vendidos...
            var c = 0;
            //Primer articulo...
            switch (Convert.ToInt16(dtv.Rows[0]["Tienda"]))
            {
                case 0:
                    tienda = "Hispacoleccion Virtual";
                    break;
                case 1:
                    tienda = "Tienda Madrid";
                    break;
                case 2:
                    tienda = "Todocoleccion";
                    break;
                case 3:
                    tienda = "Delcampe";
                    break;
                case 4:
                    tienda = "Walapop";
                    break;
            }// fin 

            myDataRow = tablaExcel.NewRow();
            myDataRow["Depositante"] = Convert.ToString(dtv.Rows[0]["Nombre"]);
            myDataRow["Titulo"] = Convert.ToString(dtv.Rows[0]["Titulo"]);
            myDataRow["Referencia"] = Convert.ToInt16(dtv.Rows[0]["Id"]);
            myDataRow["Fecha"] = dtv.Rows[0]["Fecha"];

            if (dtv.Rows[0]["ComisionVenta"] == DBNull.Value)
            { comision = 0; }
            else { comision = Convert.ToDecimal(dtv.Rows[0]["ComisionVenta"]); }

            myDataRow["Comision"] = string.Format("{0:F2}", comision); 

            if (dtv.Rows[0]["Importe"] == DBNull.Value)
            { importeOp = 0 + comision; }
            else
            {
                importeOp = Convert.ToDecimal(dtv.Rows[0]["Importe"]) + comision;
            }

            myDataRow["Precio"] = string.Format("{0:F2}", importeOp); 
            myDataRow["PuntoVenta"] = tienda;
            myDataRow["MargenDepositante"] = Convert.ToDecimal(dtv.Rows[0]["MargenDepositante"]);
            myDataRow["MargenDepositario"] = Convert.ToDecimal(dtv.Rows[0]["MargenDepositario"]);

            if (dtv.Rows[0]["GastosEnvio"] == DBNull.Value)
            { gastosEnvio = 0; }
            else { gastosEnvio = Convert.ToDecimal(dtv.Rows[0]["GastosEnvio"]); }

            myDataRow["GastosEnvio"] = string.Format("{0:F2}", gastosEnvio); 
            sumatorioImportes += importeOp;
            sumatorioComisiones += comision;
            sumatorioGastos += gastosEnvio;
            sumatorioMargenDepositante += Convert.ToDecimal(dtv.Rows[0]["MargenDepositante"]);
            sumatorioMargenDepositario += Convert.ToDecimal(dtv.Rows[0]["MargenDepositario"]);
            tablaExcel.Rows.Add(myDataRow);

            var DepositanteTemp = "";
            //Ya tenems a primera linea de todas
            // empezamos el for desde el 1
            for (int i = 1; i < maxArticulos; i++) {
                    DepositanteTemp = Convert.ToString(dtv.Rows[i]["Nombre"]);
                    DepositanteActivo = Convert.ToString(dtv.Rows[i]["Nombre"]);
                    switch (Convert.ToInt16(dtv.Rows[i]["Tienda"]))
                    {
                        case 0:
                            tienda = "Hispacoleccion Virtual";
                            break;
                        case 1:
                            tienda = "Tienda Madrid";
                            break;
                        case 2:
                            tienda = "Todocoleccion";
                            break;
                        case 3:
                            tienda = "Delcampe";
                            break;
                        case 4:
                            tienda = "Walapop";
                            break;
                    }// fin switch
                    if (string.Equals(depositantes[c], DepositanteTemp))
                    {
                        myDataRow = tablaExcel.NewRow();
                        myDataRow["Depositante"] = Convert.ToString(dtv.Rows[i]["Nombre"]);
                        myDataRow["Titulo"] = Convert.ToString(dtv.Rows[i]["Titulo"]);
                        myDataRow["Referencia"] = Convert.ToInt16(dtv.Rows[i]["Id"]);
                        myDataRow["Fecha"] = dtv.Rows[i]["Fecha"];

                        if (dtv.Rows[i]["ComisionVenta"] == DBNull.Value)
                        { comision = 0; }
                        else { comision = Convert.ToDecimal(dtv.Rows[i]["ComisionVenta"]); }

                        myDataRow["Comision"] = string.Format("{0:F2}", comision); 
                        if (dtv.Rows[i]["Importe"] == DBNull.Value)
                        { importeOp = 0 + comision; }
                        else
                        {
                            importeOp = Convert.ToDecimal(dtv.Rows[i]["Importe"]) + comision;
                        }

                        myDataRow["Precio"] = string.Format("{0:F2}", importeOp);
                        myDataRow["PuntoVenta"] = tienda;
                        myDataRow["MargenDepositante"] = Convert.ToDecimal(dtv.Rows[i]["MargenDepositante"]);
                        myDataRow["MargenDepositario"] = Convert.ToDecimal(dtv.Rows[i]["MargenDepositario"]);

                        if (dtv.Rows[i]["GastosEnvio"] == DBNull.Value)
                        { gastosEnvio = 0; }
                        else { gastosEnvio = Convert.ToDecimal(dtv.Rows[i]["GastosEnvio"]); }

                        myDataRow["GastosEnvio"] = string.Format("{0:F2}", gastosEnvio); 
                        sumatorioImportes += importeOp;
                        sumatorioComisiones += comision;
                        sumatorioGastos += gastosEnvio;
                        sumatorioMargenDepositante += Convert.ToDecimal(dtv.Rows[i]["MargenDepositante"]);
                        sumatorioMargenDepositario += Convert.ToDecimal(dtv.Rows[i]["MargenDepositario"]);
                        tablaExcel.Rows.Add(myDataRow);

                    }
                    else
                    {
                        //Nuevo vendedor. metemos totales
                        // aqui depositanteActivo YA NO ES EL ANTERIOR...
                        myDataRow = tablaExcel.NewRow();
                        myDataRow["Depositante"] = "TOTALES " + depositantes [c];
                        myDataRow["Titulo"] = " ";
                        myDataRow["Referencia"] = "";
                        myDataRow["Fecha"] = "";
                        myDataRow["Precio"] = string.Format("{0:F2}", sumatorioImportes);
                        myDataRow["PuntoVenta"] = "";
                        myDataRow["Comision"] = string.Format("{0:F2}", sumatorioComisiones);
                        myDataRow["MargenDepositante"] = string.Format("{0:F2}", sumatorioMargenDepositante); 
                        myDataRow["MargenDepositario"] = string.Format("{0:F2}", sumatorioMargenDepositario); 
                        myDataRow["GastosEnvio"] = string.Format("{0:F2}", sumatorioGastos);
                        tablaExcel.Rows.Add(myDataRow);

                        sumatorioImportes = 0m;
                        sumatorioComisiones = 0m;
                        sumatorioGastos = 0m;
                        sumatorioMargenDepositante = 0m;
                        sumatorioMargenDepositario = 0m;
                        
                        // Vamos a buscar el siguiente depositante
                        if (c < maxDepositantes) { c++; }
                      
                       DepositanteTemp = Convert.ToString(dtv.Rows[i]["Nombre"]);

                    // dos filas pafra más claridad
                    for (int f=0; f<2; f++)
                    { 
                        myDataRow = tablaExcel.NewRow();
                        myDataRow["Depositante"] = "";
                        myDataRow["Titulo"] = " ";
                        myDataRow["Referencia"] = "";
                        myDataRow["Fecha"] = "";
                        myDataRow["Precio"] = "";
                        myDataRow["PuntoVenta"] = "";
                        myDataRow["Comision"] = "";
                        myDataRow["MargenDepositante"] = "";
                        myDataRow["MargenDepositario"] = "";
                        myDataRow["GastosEnvio"] = "";
                        tablaExcel.Rows.Add(myDataRow);
                    }

                    // Metemos los datos de la primera fila, que ya tenemos...
                    myDataRow = tablaExcel.NewRow();
                    myDataRow["Depositante"] = Convert.ToString(dtv.Rows[i]["Nombre"]);
                    myDataRow["Titulo"] = Convert.ToString(dtv.Rows[i]["Titulo"]);
                    myDataRow["Referencia"] = Convert.ToInt16(dtv.Rows[i]["Id"]);
                    myDataRow["Fecha"] = dtv.Rows[i]["Fecha"];

                    if (dtv.Rows[i]["ComisionVenta"] == DBNull.Value)
                    { comision = 0; }
                    else { comision = Convert.ToDecimal(dtv.Rows[i]["ComisionVenta"]); }

                    myDataRow["Comision"] = comision;

                    if (dtv.Rows[i]["Importe"] == DBNull.Value)
                    { importeOp = 0 + comision; }
                    else
                    {
                        importeOp = Convert.ToDecimal(dtv.Rows[i]["Importe"]) + comision;
                    }

                    myDataRow["Precio"] = string.Format("{0:F2}", importeOp);
                    myDataRow["PuntoVenta"] = tienda;
                    myDataRow["MargenDepositante"] = Convert.ToDecimal(dtv.Rows[i]["MargenDepositante"]);
                    myDataRow["MargenDepositario"] = Convert.ToDecimal(dtv.Rows[i]["MargenDepositario"]);

                    if (dtv.Rows[i]["GastosEnvio"] == DBNull.Value)
                    { gastosEnvio = 0; }
                    else { gastosEnvio = Convert.ToDecimal(dtv.Rows[i]["GastosEnvio"]); }

                    myDataRow["GastosEnvio"] = gastosEnvio;
                    sumatorioImportes += importeOp;
                    sumatorioComisiones += comision;
                    sumatorioGastos += gastosEnvio;
                    sumatorioMargenDepositante += Convert.ToDecimal(dtv.Rows[i]["MargenDepositante"]);
                    sumatorioMargenDepositario += Convert.ToDecimal(dtv.Rows[i]["MargenDepositario"]);
                    tablaExcel.Rows.Add(myDataRow);
                }


               // }// fin for articulos
            }// fin for depositantes

            myDataRow = tablaExcel.NewRow();
            myDataRow["Depositante"] = "TOTALES ";
            myDataRow["Titulo"] = " ";
            myDataRow["Referencia"] = "";
            myDataRow["Fecha"] = "";
            myDataRow["Precio"] = sumatorioImportes;
            myDataRow["PuntoVenta"] = "";
            myDataRow["Comision"] = sumatorioComisiones;
            myDataRow["MargenDepositante"] = sumatorioMargenDepositante;
            myDataRow["MargenDepositario"] = sumatorioMargenDepositario;
            myDataRow["GastosEnvio"] = gastosEnvio;
            tablaExcel.Rows.Add(myDataRow);

            sumatorioImportes = 0m;
            sumatorioComisiones = 0m;
            sumatorioGastos = 0m;
            sumatorioMargenDepositante = 0m;
            sumatorioMargenDepositario = 0m;

            myDataRow = tablaExcel.NewRow();

            myDataRow["Depositante"] = "";
            myDataRow["Titulo"] = " ";
            myDataRow["Referencia"] = "";
            myDataRow["Fecha"] = "";
            myDataRow["Precio"] = "";
            myDataRow["PuntoVenta"] = "";

            myDataRow["Comision"] = "";
            myDataRow["MargenDepositante"] = "";
            myDataRow["MargenDepositario"] = "";
            myDataRow["GastosEnvio"] = "";
            tablaExcel.Rows.Add(myDataRow);


            var grid = new GridView { DataSource = tablaExcel };
            grid.ForeColor = ColorTranslator.FromHtml("#333333");
            grid.FooterStyle.BackColor = ColorTranslator.FromHtml("#507CD1");
            grid.HeaderStyle.ForeColor = Color.White;
            grid.HeaderStyle.BackColor = ColorTranslator.FromHtml("#507CD1");
            grid.HeaderStyle.Font.Bold = true;
            grid.RowStyle.BackColor = ColorTranslator.FromHtml("#EFF3FB");
            grid.AlternatingRowStyle.BackColor = Color.White;
            grid.AlternatingRowStyle.ForeColor = ColorTranslator.FromHtml("#000");
            grid.DataBind();

            grid.HeaderRow.BackColor = Color.White;
            foreach (TableCell cell in grid.HeaderRow.Cells)
            {
                cell.BackColor = grid.HeaderStyle.BackColor;
            }
            foreach (GridViewRow row in grid.Rows)
            {
                row.BackColor = Color.White;
                foreach (TableCell cell in row.Cells)
                {
                    if (row.RowIndex % 2 == 0)
                    {
                        cell.BackColor = grid.AlternatingRowStyle.BackColor;
                    }
                    else
                    {
                        cell.BackColor = grid.RowStyle.BackColor;
                    }
                    cell.CssClass = "textmode";
                }
            }
            Response.ClearContent();
            Response.Buffer = true;
            Response.AddHeader("content-disposition", "attachment; filename=HispaVentasDetallePorVendedores.xls");
            var sw = new StringWriter();
            var htw = new HtmlTextWriter(sw);
            grid.RenderControl(htw);
            return Content(sw.ToString(), "application/ms-excel");

        }