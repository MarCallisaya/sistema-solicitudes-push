

<?php
// Creado: 13 1 26
defined('BASEPATH') OR exit('No direct script access allowed');
class M_metricas extends CI_Model
{
    public function por_estado()
    {
        return $this->db->query("
            SELECT estado_actual AS label, COUNT(*) AS total
            FROM solicitud
            WHERE activo = TRUE
            GROUP BY estado_actual
        ")->result_array();
    }
    public function por_tipo()
    {
        return $this->db->query("
            SELECT ts.nombre AS label, COUNT(*) AS total
            FROM solicitud s
            JOIN tipo_solicitud ts ON ts.id_tipo_solicitud = s.tipo_solicitud_id
            WHERE s.activo = TRUE
            GROUP BY ts.nombre
        ")->result_array();
    }
    public function por_dia()
    {
        return $this->db->query("
            SELECT 
                EXTRACT(DOW FROM fecha_registro) AS dia,
                COUNT(*) AS total
            FROM solicitud
            WHERE activo = TRUE
            GROUP BY dia
            ORDER BY dia
        ")->result_array();
    }
    public function ultimos_30_dias()
    {
        return $this->db->query("
            SELECT 
                DATE(fecha_registro) AS fecha,
                COUNT(*) AS total
            FROM solicitud
            WHERE activo = TRUE
              AND fecha_registro >= CURRENT_DATE - INTERVAL '30 days'
            GROUP BY fecha
            ORDER BY fecha
        ")->result_array();
    }
}