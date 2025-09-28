using System.ComponentModel.DataAnnotations.Schema;
using System.ComponentModel.DataAnnotations;

namespace TechFixApplication.Models.Entities
{
    public class Orders
    {
        [Key]
        public Guid Id { get; set; }

        [Required]
        [ForeignKey("Quotation")]
        public Guid QuotationId { get; set; }

        [Required]
        [ForeignKey("Supplier")]
        public Guid SupplierId { get; set; }

        [Required]
        [StringLength(100)]
        public string ItemName { get; set; }

        [Required]
        public string Description { get; set; }

        [Required]
        [Column(TypeName = "decimal(18,2)")]
        public decimal Price { get; set; }

        [Required]
        public int Quantity { get; set; }

        [Required]
        [Column(TypeName = "decimal(18,2)")]
        public decimal TotalValue { get; set; }

        [Required]
        public DateTime OrderDate { get; set; } = DateTime.Now;
    }
}
