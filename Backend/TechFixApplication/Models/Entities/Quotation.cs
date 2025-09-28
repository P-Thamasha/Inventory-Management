using System.ComponentModel.DataAnnotations.Schema;
using System.ComponentModel.DataAnnotations;

namespace TechFixApplication.Models.Entities
{
    public class Quotation
    {
        [Key]
        public Guid Id { get; set; } // Unique identifier for the quotation

        [ForeignKey("Supplier")]
        public Guid SupplierId { get; set; }

        [Required]
        [StringLength(100)]
        public string CompanyName { get; set; } // Name of the company

        [Required]
        [StringLength(300)]
        public string CompanyAddress { get; set; } // Address of the company

        [Required]
        [Phone]
        public string PhoneNumber { get; set; } // Contact number of the company

        [Required]
        [StringLength(100)]
        public string ItemName { get; set; } // Name of the item being quoted

        [Required]
        [StringLength(500)]
        public string Description { get; set; } // Description of the item

        [Required]
        [Column(TypeName = "decimal(18,2)")]
        public decimal DiscountAmount { get; set; } // Discount price

        [Required]
        [Column(TypeName = "decimal(18,2)")]
        public decimal Price { get; set; } // Quoted price

    }
}
