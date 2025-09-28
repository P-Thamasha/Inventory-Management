using System.ComponentModel.DataAnnotations.Schema;
using System.ComponentModel.DataAnnotations;

namespace TechFixApplication.Models.Entities
{
    public class Inventory
    {
        [Key]
        public Guid Id { get; set; }

        [ForeignKey("Supplier")]
        public Guid SupplierId { get; set; } 

        [Required]
        [StringLength(100)]
        public string ItemName { get; set; } 

        [Required]
        public int Quantity { get; set; } 

        [Required]
        [Column(TypeName = "decimal(18,2)")]
        public decimal Price { get; set; }
    }
}
