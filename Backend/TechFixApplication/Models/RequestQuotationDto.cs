using System.ComponentModel.DataAnnotations;

namespace TechFixApplication.Models
{
    public class RequestQuotationDto
    {
        [Required]
        public string ItemName { get; set; }

        [Required]
        [StringLength(500)]
        public string Description { get; set; }

        [Required]
        public DateTime DateNeeded { get; set; }

        [Required]
        [Range(1, int.MaxValue, ErrorMessage = "Quantity must be greater than 0.")]
        public int Quantity { get; set; }
    }
}
