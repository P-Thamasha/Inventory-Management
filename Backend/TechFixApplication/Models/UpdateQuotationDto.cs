using System.ComponentModel.DataAnnotations;

namespace TechFixApplication.Models
{
    public class UpdateQuotationDto
    {
        [Required]
        public Guid SupplierId { get; set; }

        [Required]
        public string CompanyName { get; set; }

        [Required]
        public string CompanyAddress { get; set; }

        [Required]
        [Phone]
        public string PhoneNumber { get; set; }

        [Required]
        public string ItemName { get; set; }

        [Required]
        public string Description { get; set; }

        [Required]
        public decimal Price { get; set; }
    }
}
