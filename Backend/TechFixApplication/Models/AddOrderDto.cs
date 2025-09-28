using System.ComponentModel.DataAnnotations;

namespace TechFixApplication.Models
{
    public class AddOrderDto
    {
        [Required]
        public Guid QuotationId { get; set; }

        [Required]
        public Guid SupplierId { get; set; } // Ensure this matches the field sent from the frontend

        [Required]
        [StringLength(100)]
        public string ItemName { get; set; }

        [StringLength(500)]
        public string Description { get; set; }

        [Required]
        [Range(0.01, double.MaxValue)]
        public decimal Price { get; set; }

        [Required]
        [Range(1, int.MaxValue)]
        public int Quantity { get; set; }

        [Required]
        [Range(0.01, double.MaxValue)]
        public decimal TotalValue { get; set; }
    }
}
